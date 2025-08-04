<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\TransmitalHeader;
use App\Models\TransmitalDetails;

class PertaminaController extends Controller
{
    public function dashboard()
    {
        $messages = DB::table('transmital_details')
            ->join('transmital_headers', 'transmital_details.transmital_header_id', '=', 'transmital_headers.id')
            ->select(
                'transmital_details.id',
                'transmital_details.document_number',
                'transmital_details.title',
                'transmital_details.revision',
                'transmital_details.status',
                'transmital_details.document_path',
                'transmital_details.notes', // ✅ tambahkan kolom notes
                'transmital_details.created_at',
                'transmital_headers.from_department'
            )
            ->where('transmital_headers.to_department', auth()->user()->division)
            ->orderByDesc('transmital_details.created_at')
            ->get();

        return view('pertamina.dashboard', compact('messages'));
    }

    public function uploadForm()
    {
        $transmittals = DB::table('transmital_headers')
            ->join('transmital_details', 'transmital_headers.id', '=', 'transmital_details.transmital_header_id')
            ->where('transmital_headers.from_department', auth()->user()->division)
            ->select(
                'transmital_headers.transmittal_number',
                'transmital_details.document_number',
                'transmital_details.title',
                'transmital_details.document_path',
                'transmital_details.status'
            )
            ->orderByDesc('transmital_headers.created_at')
            ->get();

        return view('pertamina.upload', compact('transmittals'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document_number' => 'required|string',
            'title' => 'required|string',
            'revision' => 'required|integer|min:0',
            'status' => 'required|string|in:approve,pending',
            'notes' => 'nullable|string',
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        // Simpan file PDF ke disk public/documents
        $path = $request->file('file')->store('documents', 'public');

        // ✅ Ambil divisi dari salah satu user yang memiliki role admin
        $toDepartment = DB::table('users')->where('role', 'admin')->value('division') ?? 'ADMIN';
        $toDivisionCode = strtoupper(str_replace(' ', '', $toDepartment));

        // ✅ Generate nomor transmital menggunakan to_department (divisi tujuan)
        $latest = TransmitalHeader::orderBy('id', 'desc')->first();
        $urut = str_pad(($latest?->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);
        $year = now()->format('Y');
        $month = now()->format('m');
        $transmittalNumber = "TRSM{$urut}/TA{$year}/{$toDivisionCode}/{$month}/{$year}";

        // ✅ Simpan ke tabel transmital_headers
        $headerId = TransmitalHeader::create([
            'transmittal_number' => $transmittalNumber,
            'po_list_id' => null,
            'transmittal_date' => now(),
            'from_department' => auth()->user()->division,
            'to_department' => $toDepartment,
            'remarks' => null,
            'status' => $request->status,
        ])->id;

        // ✅ Simpan ke tabel transmital_details
        TransmitalDetails::create([
            'transmital_header_id' => $headerId,
            'document_number' => $request->document_number,
            'title' => $request->title,
            'revision' => $request->revision,
            'document_path' => $path,
            'notes' => $request->notes,
            'is_sent' => true,
            'is_read' => false,
            'status' => $request->status,
        ]);

        return redirect()->route('pertamina.upload.form')->with('success', 'Dokumen berhasil dikirim ke divisi ' . $toDepartment . '.');
    }
}
