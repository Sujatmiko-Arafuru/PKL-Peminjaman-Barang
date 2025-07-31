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
                <h1 class="dashboard-title mb-0"><i class="bi bi-eye me-2"></i>Detail Peminjaman</h1>
                <a href="{{ route('cekStatus.form') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person me-2"></i>Data Peminjam</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4 text-center">
                                    @if($peminjaman->foto_peminjam)
                                        <img src="{{ asset('storage/' . $peminjaman->foto_peminjam) }}" alt="Foto Peminjam" class="img-fluid rounded" style="max-width: 120px; max-height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                            <i class="bi bi-person text-muted" style="font-size: 2.5rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Nama:</strong></span>
                                    <span>{{ $peminjaman->nama }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>No. Telepon:</strong></span>
                                    <span>{{ $peminjaman->no_telp }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Unit/Jurusan:</strong></span>
                                    <span>{{ $peminjaman->unit }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Nama Kegiatan:</strong></span>
                                    <span>{{ $peminjaman->nama_kegiatan }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Tanggal Pinjam:</strong></span>
                                    <span>{{ $peminjaman->tanggal_mulai }} s/d {{ $peminjaman->tanggal_selesai }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Status:</strong></span>
                                    <span class="badge {{ $peminjaman->status == 'dikembalikan' ? 'bg-success' : ($peminjaman->status == 'disetujui' ? 'bg-primary' : ($peminjaman->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark')) }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </li>
                                @if($peminjaman->status == 'disetujui')
                                <li class="list-group-item text-center">
                                    <form action="{{ route('pengembalian.ajukan', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Ajukan pengembalian untuk peminjaman ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success"><i class="bi bi-arrow-repeat me-2"></i>Ajukan Pengembalian</button>
                                    </form>
                                </li>
                                @elseif($peminjaman->status == 'pengembalian_diajukan')
                                <li class="list-group-item text-center">
                                    <span class="badge bg-warning text-dark">Pengembalian sedang menunggu persetujuan admin.</span>
                                </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Bukti:</strong></span>
                                    <span>
                                        @if($peminjaman->bukti)
                                            <a href="{{ asset('storage/' . $peminjaman->bukti) }}" target="_blank" class="btn btn-sm btn-info text-white">
                                                <i class="bi bi-file-earmark"></i> Lihat File
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </span>
                                </li>
                            </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Daftar Barang yang Dipinjam</h5>
                        </div>
                        <div class="card-body">
                            @if($peminjaman->details->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($peminjaman->details as $detail)
                                                <tr>
                                                    <td>{{ $detail->barang->nama ?? '-' }}</td>
                                                    <td><span class="badge bg-primary">{{ $detail->jumlah }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Tidak ada barang yang dipinjam.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 