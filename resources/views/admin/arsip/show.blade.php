@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Detail History Peminjaman/Pengembalian</h2>
<a href="{{ route('admin.arsip.index') }}" class="btn btn-outline-primary mb-3">&larr; Kembali ke Arsip</a>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Data Peminjam</h5>
                <p class="mb-1">Nama: <b>{{ $peminjaman->nama }}</b></p>
                <p class="mb-1">No HP: <b>{{ $peminjaman->no_telp }}</b></p>
                <p class="mb-1">Unit/Jurusan: <b>{{ $peminjaman->unit }}</b></p>
                <p class="mb-1">Nama Kegiatan: <b>{{ $peminjaman->nama_kegiatan }}</b></p>
                <p class="mb-1">Tanggal Pinjam: <b>{{ format_tanggal($peminjaman->tanggal_mulai) }} s/d {{ format_tanggal($peminjaman->tanggal_selesai) }}</b></p>
                <p class="mb-1">Kode Peminjaman: <b>{{ $peminjaman->kode_peminjaman }}</b></p>
                <p class="mb-1">Lampiran: 
                    @if($peminjaman->bukti)
                        <a href="{{ asset('storage/' . $peminjaman->bukti) }}" target="_blank" class="btn btn-sm btn-info text-white">Lihat Bukti</a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </p>
                <p class="mb-1">Status: <span class="badge {{ $peminjaman->status == 'dikembalikan' ? 'bg-success' : ($peminjaman->status == 'disetujui' ? 'bg-primary' : ($peminjaman->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark')) }}">{{ ucfirst($peminjaman->status) }}</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Barang yang Dipinjam</h5>
                <ul class="list-group">
                    @foreach($peminjaman->details as $detail)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $detail->barang->nama ?? '-' }}
                        <span class="badge bg-primary">{{ $detail->jumlah }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 