<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Vendor;
use App\Models\EPS;
use App\Models\DocReadiness;
use App\Models\TransmitalHeader;
use App\Models\TransmitalDetails;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VendorImport;

class AdminController extends Controller
{
    private function generateTransmittalNumber($toDivision)
    {
        $lastId = TransmitalHeader::max('id') ?? 0;
        $newId = $lastId + 1;
        $tahun = now()->format('Y');
        $bulan = now()->format('m');
        $division = strtoupper(str_replace(' ', '', $toDivision ?: 'UNKNOWN'));

        return sprintf('TRSM%05d/TA%s/%s/%s/%s', $newId, $tahun, $division, $bulan, $tahun);
    }

    public function dashboard()
    {
        $notifikasi = DB::table('transmital_details')
            ->join('transmital_headers', 'transmital_details.transmital_header_id', '=', 'transmital_headers.id')
            ->join('users', 'transmital_headers.from_department', '=', 'users.division')
            ->select(
                'transmital_details.id',
                'transmital_details.document_number',
                'transmital_details.title',
                'transmital_details.revision',
                'transmital_details.notes',
                'transmital_details.is_read',
                'transmital_details.status',
                'transmital_details.document_path',
                'transmital_details.created_at',
                'users.division as dari_departemen',
                'users.role as dari_role'
            )
            ->where('transmital_details.is_read', false)
            ->where('users.role', 'PERTAMINA')
            ->orderByDesc('transmital_details.created_at')
            ->get();

        $vendor = Vendor::all();
        $divisiPertamina = User::where('role', 'PERTAMINA')->select('division')->distinct()->get();

        return view('admin.dashboard', compact('notifikasi', 'vendor', 'divisiPertamina'));
    }

    public function markAsRead($id)
    {
        TransmitalDetails::where('id', $id)->update(['is_read' => true]);
        return redirect()->route('admin.dashboard')->with('success', 'Dokumen ditandai sebagai dibaca.');
    }

    public function rekap(Request $request)
    {
        $sortBy = $request->input('sort', 'transmittal_date');
        $sortDir = $request->input('direction', 'desc');
        $discipline = $request->input('discipline');

        $rekaps = TransmitalHeader::with(['details' => function ($query) use ($discipline) {
            if ($discipline) {
                $query->where('discipline', $discipline);
            }
        }])->orderBy($sortBy, $sortDir)->get();

        return view('admin.rekap', compact('rekaps', 'sortBy', 'sortDir', 'discipline'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        TransmitalHeader::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $header = TransmitalHeader::with('details')->findOrFail($id);
        foreach ($header->details as $detail) {
            if ($detail->document_path && Storage::disk('public')->exists($detail->document_path)) {
                Storage::disk('public')->delete($detail->document_path);
            }
        }
        $header->details()->delete();
        $header->delete();

        return back()->with('success', 'Rekap dokumen berhasil dihapus.');
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|unique:users,phone',
            'division' => 'nullable|string',
            'role' => 'required|in:admin,PERTAMINA',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $data['division'] = strtoupper($data['division'] ?? '');
        $data['password'] = Hash::make($data['password']);

        User::create($data);
        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function destroyUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function vendors()
    {
        $vendors = Vendor::orderBy('name')->get();
        return view('admin.vendor', compact('vendors'));
    }

    public function editVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('admin.vendor_edit', compact('vendor'));
    }

    public function updateVendor(Request $request, $id)
    {
        $data = $request->validate([
            'pic_name' => 'required|string',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string'
        ]);
        Vendor::findOrFail($id)->update($data);
        return redirect()->route('admin.vendors')->with('success', 'Vendor berhasil diperbarui.');
    }

    public function importVendors(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        try {
            Excel::import(new VendorImport, $request->file('file'));
            return back()->with('success', 'Data vendor berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal impor vendor: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_vendors', []);
        if ($ids) {
            Vendor::whereIn('id', $ids)->delete();
            return back()->with('success', 'Vendor pilihan berhasil dihapus.');
        }
        return back()->with('error', 'Tidak ada vendor dipilih.');
    }

    public function eps()
    {
        $eps = EPS::with('vendor')->get();
        return view('admin.eps', compact('eps'));
    }

    public function docReadiness()
    {
        $vendors = Vendor::orderBy('name')->get();
        $documents = DocReadiness::latest()->get();
        $latest = TransmitalHeader::max('id') ?? 0;
        $genNum = 'TRM-' . str_pad($latest + 1, 4, '0', STR_PAD_LEFT);

        return view('admin.doc_readiness', compact('vendors', 'documents', 'genNum'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'po_list_number' => 'required|string',
            'document_number' => 'required|string',
            'document_title' => 'required|string',
            'to_department' => 'required|string',
            'revision' => 'nullable|integer|min:0',
            'file' => 'required|mimes:pdf|max:5120',
            'notes' => 'nullable|string',
            'tag_number' => 'nullable|string',
            'fase' => 'required|integer|min:1',
            'discipline' => 'required|string|max:100',
        ]);

        $path = $request->file('file')->store('documents', 'public');
        $trNo = $this->generateTransmittalNumber($request->to_department);
        $vendor = Vendor::findOrFail($request->vendor_id);

        $header = TransmitalHeader::create([
            'transmittal_number' => $trNo,
            'po_list_id' => null,
            'transmittal_date' => now(),
            'from_department' => auth()->user()->division,
            'to_department' => $request->to_department,
            'remarks' => 'PO List: ' . $request->po_list_number,
            'status' => 'dikirim',
        ]);

        TransmitalDetails::create([
            'transmital_header_id' => $header->id,
            'document_number' => $request->document_number,
            'title' => $request->document_title,
            'revision' => $request->revision ?? 0,
            'document_path' => $path,
            'notes' => $request->notes,
            'vendor_name' => $vendor->name,
            'po_number' => $request->po_list_number,
            'tag_number' => $request->tag_number,
            'is_sent' => true,
            'is_read' => false,
            'status' => 'Baru',
            'discipline' => $request->discipline,
        ]);

        return back()->with('success', 'Dokumen berhasil diupload dan tersimpan.');
    }

    public function replyDoc(Request $request)
    {
        $request->validate([
            'transmital_detail_id' => 'required|exists:transmital_details,id',
            'vendor_id' => 'required|exists:vendors,id',
            'title' => 'required|string|max:255',
            'document_number' => 'required|string|max:100',
            'po_number' => 'required|string|max:100',
            'revision' => 'required|integer|min:0',
            'tag_number' => 'required|string|max:100',
            'document_file' => 'required|file|mimes:pdf|max:10240',
            'notes' => 'nullable|string',
            'fase' => 'required|integer|min:1',
            'discipline' => 'required|string|max:100',
        ]);

        $filePath = $request->file('document_file')->store('documents', 'public');
        $vendor = Vendor::findOrFail($request->vendor_id);
        $originalDetail = TransmitalDetails::with('header')->findOrFail($request->transmital_detail_id);
        $targetDivision = $originalDetail->header->from_department ?? 'PERTAMINA';
        $transmittalNumber = $this->generateTransmittalNumber($targetDivision);

        $header = TransmitalHeader::create([
            'transmittal_number' => $transmittalNumber,
            'po_list_id' => null,
            'transmittal_date' => now(),
            'from_department' => auth()->user()->division ?? 'ADMIN',
            'to_department' => $targetDivision,
            'remarks' => 'Balasan dokumen dari vendor: ' . $vendor->name,
            'status' => 'dikirim',
        ]);

        TransmitalDetails::create([
            'transmital_header_id' => $header->id,
            'document_number' => $request->document_number,
            'title' => $request->title,
            'revision' => $request->revision,
            'document_path' => $filePath,
            'notes' => $request->notes,
            'vendor_name' => $vendor->name,
            'po_number' => $request->po_number,
            'tag_number' => $request->tag_number,
            'is_sent' => true,
            'is_read' => false,
            'status' => 'Dibalas',
            'discipline' => $request->discipline,
        ]);

        $originalDetail->update([
            'is_read' => true,
            'status' => 'Dibalas',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Dokumen berhasil dibalas dan dikirim ke divisi tujuan.');
    }

    public function kirimPesan(Request $request)
    {
        $data = $request->validate([
            'to_division' => 'required|string',
            'message' => 'required|string',
        ]);
        DB::table('notifikasi')->insert([
            'from' => auth()->user()->division,
            'to' => $data['to_division'],
            'message' => $data['message'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Pesan berhasil dikirim ke divisi ' . $data['to_division']);
    }

    public function searchVendor(Request $request)
    {
        $vendors = Vendor::query()
            ->when($request->query, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->query}%")
                    ->orWhere('pic_name', 'like', "%{$request->query}%");
            })
            ->when(in_array($request->sort, ['name', 'pic_name']), function ($q) use ($request) {
                $q->orderBy($request->sort);
            }, function ($q) {
                $q->orderBy('name');
            })
            ->get();

        return view('admin.vendor', compact('vendors'));
    }
}
