@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3 col-lg-2">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3"><i class="bi bi-list"></i> Menu</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam me-2"></i>List Barang
                        </a>
                        <a href="{{ route('keranjang.index') }}" class="btn btn-outline-primary position-relative">
                            <i class="bi bi-cart3 me-2"></i>Keranjang
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </a>
                        <a href="{{ route('list.peminjam') }}" class="btn btn-outline-info">
                            <i class="bi bi-people me-2"></i>List Peminjam
                        </a>
                        <a href="{{ route('cekStatus.form') }}" class="btn btn-success {{ request()->routeIs('cekStatus.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-repeat me-2"></i>Pengembalian
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="dashboard-title mb-0"><i class="bi bi-search me-2"></i>Hasil Pencarian Kegiatan</h1>
                <a href="{{ route('cekStatus.form') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Hasil pencarian untuk: <strong>"{{ $request->nama_kegiatan }}"</strong>
            </div>
            
            @if($peminjamans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Kode Peminjaman</th>
                                <th>Nama</th>
                                <th>Nama Kegiatan</th>
                                <th>Tanggal Kegiatan</th>
                                <th>Status</th>
                                <th>Barang Dipinjam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamans as $peminjaman)
                            <tr>
                                <td><span class="badge bg-dark">{{ $peminjaman->kode_peminjaman }}</span></td>
                                <td>{{ $peminjaman->nama }}</td>
                                <td>{{ $peminjaman->nama_kegiatan }}</td>
                                <td>{{ $peminjaman->tanggal_mulai }} s/d {{ $peminjaman->tanggal_selesai }}</td>
                                <td>
                                    <span class="badge {{ $peminjaman->status == 'dikembalikan' ? 'bg-success' : ($peminjaman->status == 'disetujui' ? 'bg-primary' : ($peminjaman->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark')) }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($peminjaman->details as $detail)
                                        <span class="badge bg-info text-dark mb-1">{{ $detail->barang->nama ?? '-' }} ({{ $detail->jumlah }})</span>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('cekStatus.detail', $peminjaman->id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Tidak ditemukan peminjaman dengan nama kegiatan "{{ $request->nama_kegiatan }}".
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 