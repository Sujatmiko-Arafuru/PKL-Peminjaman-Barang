@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="dashboard-title mb-3"><i class="bi bi-clipboard-data me-2"></i>Hasil Cek Status Peminjaman</h1>
    @if($peminjaman)
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> Data peminjaman ditemukan.</div>
        <ul class="list-group mb-3 shadow-sm">
            <li class="list-group-item"><b>Nama:</b> {{ $peminjaman->nama }}</li>
            <li class="list-group-item"><b>Email:</b> {{ $peminjaman->email }}</li>
            <li class="list-group-item"><b>No. Telepon:</b> {{ $peminjaman->no_telp }}</li>
            <li class="list-group-item"><b>Unit/Jurusan:</b> {{ $peminjaman->unit }}</li>
            <li class="list-group-item"><b>Tanggal Pinjam:</b> {{ $peminjaman->tanggal_mulai }} s/d {{ $peminjaman->tanggal_selesai }}</li>
            <li class="list-group-item"><b>Keperluan:</b> {{ $peminjaman->keperluan }}</li>
            <li class="list-group-item"><b>Status:</b> <span class="badge {{ $peminjaman->status == 'dikembalikan' ? 'bg-success' : ($peminjaman->status == 'disetujui' ? 'bg-primary' : ($peminjaman->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark')) }}">{{ ucfirst($peminjaman->status) }}</span></li>
            <li class="list-group-item"><b>Kode Unik:</b> <span class="badge bg-dark">{{ $peminjaman->kode_unik }}</span></li>
        </ul>
    @else
        <div class="alert alert-danger"><i class="bi bi-x-circle"></i> Data peminjaman tidak ditemukan. Pastikan email dan kode unik benar.</div>
    @endif
    <a href="{{ route('cekStatus.form') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Cek Data Lain</a>
</div>
@endsection 