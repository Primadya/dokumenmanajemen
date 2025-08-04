@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Notifikasi dari Admin</h1>
    @forelse($notifications as $notif)
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="font-bold text-lg">{{ $notif->transmittal_number }}</h2>
            <p>Dari: {{ $notif->from_department }}</p>
            <p>Tanggal: {{ $notif->transmittal_date }}</p>
            <p>Status: <span class="text-sm px-2 py-1 rounded bg-blue-100 text-blue-700">{{ $notif->status }}</span></p>
            <a href="{{ route('pertamina.detail', $notif->id) }}" class="text-blue-600 underline">Lihat Dokumen</a>
        </div>
    @empty
        <p class="text-gray-600">Tidak ada notifikasi.</p>
    @endforelse
</div>
@endsection