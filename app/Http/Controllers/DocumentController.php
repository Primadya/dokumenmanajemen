<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DocReadiness;
use App\Models\Vendor;
use App\Models\TransmitalHeader;
use App\Models\TransmitalDetails;
use App\Models\User;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = DocReadiness::query();

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%$search%")
                  ->orWhere('document_title', 'like', "%$search%")
                  ->orWhere('tag_number', 'like', "%$search%");
            });
        }

        if ($request->has('sort')) {
            if ($request->sort == 'latest') {
                $query->orderBy('updated_at', 'desc');
            } elseif ($request->sort == 'oldest') {
                $query->orderBy('updated_at', 'asc');
            }
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        $documents = $query->get();
        $vendors = Vendor::orderBy('name')->get();
        $divisiPertamina = User::where('role', 'PERTAMINA')->select('division')->distinct()->get();

        return view('admin.doc_readiness', compact('documents', 'vendors', 'divisiPertamina'));
    }

public function upload(Request $request)
{
    $request->validate([
        'vendor_id' => 'required|exists:vendors,id',
        'document_title' => 'required|string|max:255',
        'document_number' => 'required|string|max:100',
        'po_list_number' => 'nullable|string|max:100',
        'revision' => 'required|string|max:50',
        'tag_number' => 'required|string|max:100',
        'fase' => 'required|integer|min:1',
        'discipline' => 'nullable|string|max:100', // ✅ validasi tambahan
        'file_dokumen' => 'required|file|mimes:pdf|max:10240',
    ]);

    $filePath = $request->file('file_dokumen')->store('documents', 'public');
    $vendor = Vendor::findOrFail($request->vendor_id);

    DocReadiness::create([
        'vendor_id' => $vendor->id,
        'vendor_name' => $vendor->name,
        'document_title' => $request->document_title,
        'document_number' => $request->document_number,
        'po_list_number' => $request->po_list_number,
        'revision' => $request->revision,
        'tag_number' => $request->tag_number,
        'fase' => $request->fase,
        'discipline' => $request->discipline, // ✅ simpan disiplin
        'file_path' => $filePath,
        'transmittal_number' => null,
    ]);

    return redirect()->route('admin.doc.readiness')->with('success', 'Dokumen berhasil diupload.');
}



    private function generateTransmittalNumber($toDivision)
    {
        $lastId = TransmitalHeader::max('id') ?? 0;
        $newId = $lastId + 1;
        $tahun = now()->format('Y');
        $bulan = now()->format('m');
        $division = strtoupper(str_replace(' ', '', $toDivision));

        return sprintf('TRSM%05d/TA%s/%s/%s/%s', $newId, $tahun, $division, $bulan, $tahun);
    }

    public function sendToPertamina(Request $request, $id)
    {
        $request->validate([
            'to_department' => 'required|string'
        ]);

        $document = DocReadiness::findOrFail($id);

        if (!$document->document_title || !$document->document_number) {
            return back()->with('error', 'Data dokumen tidak lengkap (judul atau nomor dokumen kosong).');
        }

        $transmittalNumber = $this->generateTransmittalNumber($request->to_department);

        $header = TransmitalHeader::create([
            'transmittal_number' => $transmittalNumber,
            'po_list_id' => null,
            'transmittal_date' => now(),
            'from_department' => auth()->user()->division ?? 'Admin',
            'to_department' => $request->to_department,
            'remarks' => 'Dokumen dikirim dari fitur Kesiapan Dokumen',
            'status' => 'pending',
        ]);

        TransmitalDetails::create([
            'transmital_header_id' => $header->id,
            'document_number' => $document->document_number,
            'title' => $document->document_title,
            'revision' => $document->revision ?? '-',
            'document_path' => $document->file_path,
            'is_sent' => true,
            'is_read' => false,
            'status' => 'Dikirim',
            'vendor_name' => $document->vendor_name ?? null,
            'po_number' => $document->po_list_number ?? null,
            'tag_number' => $document->tag_number ?? null,
            'notes' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $document->update([
            'transmittal_number' => $transmittalNumber,
        ]);

        return redirect()->route('admin.doc.readiness')->with('success', 'Dokumen berhasil dikirim ke ' . $request->to_department);
    }

    public function destroy($id)
    {
        $doc = DocReadiness::findOrFail($id);

        if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();

        return redirect()->route('admin.doc.readiness')->with('success', 'Dokumen berhasil dihapus.');
    }
}
