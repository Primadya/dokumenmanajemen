<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $documents = DB::table('transmital_detail')
            ->join('transmital_header', 'transmital_detail.transmital_header_id', '=', 'transmital_header.id')
            ->when($user->role === 'PERTAMINA', function ($query) use ($user) {
                return $query->where('transmital_header.from_department', $user->department);
            })
            ->when($user->role === 'admin', function ($query) use ($user) {
                return $query->where('transmital_header.to_department', $user->department);
            })
            ->select(
                'transmital_detail.document_number',
                'transmital_detail.title',
                'transmital_detail.revision',
                'transmital_detail.is_sent',
                'transmital_detail.is_read',
                'transmital_header.from_department',
                'transmital_header.to_department',
                'transmital_header.transmital_date',
                'transmital_detail.document_path'
            )
            ->orderByDesc('transmital_header.transmital_date')
            ->get();

        return view('history.index', compact('documents'));
    }
}