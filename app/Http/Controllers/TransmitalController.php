<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransmitalHeader;

class TransmitalController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Selesai'
        ]);

        $rekap = TransmitalHeader::findOrFail($id);
        $rekap->update(['status' => $request->status]);

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rekap = TransmitalHeader::findOrFail($id);
        $rekap->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
