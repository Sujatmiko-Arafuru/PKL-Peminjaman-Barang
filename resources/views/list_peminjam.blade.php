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
                        <a href="{{ route('list.peminjam') }}" class="btn btn-info {{ request()->routeIs('list.peminjam*') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i>List Peminjam
                        </a>
                        <a href="{{ route('cekStatus.form') }}" class="btn btn-outline-success">
                            <i class="bi bi-arrow-repeat me-2"></i>Pengembalian
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-people me-2"></i>List Peminjam</h1>
            
            <!-- Filter Form -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control" placeholder="Cari nama kegiatan..." value="{{ request('nama_kegiatan') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <a href="{{ route('list.peminjam') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- List Peminjam -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Peminjam</h5>
                </div>
                <div class="card-body">
                    @if($peminjamans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tanggal Kegiatan</th>
                                        <th>Status</th>
                                        <th>No. HP</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamans as $peminjaman)
                                    <tr>
                                        <td><strong>{{ $peminjaman->nama }}</strong></td>
                                        <td>{{ Str::limit($peminjaman->nama_kegiatan, 30) }}</td>
                                        <td>{{ $peminjaman->tanggal_mulai }} s/d {{ $peminjaman->tanggal_selesai }}</td>
                                        <td>
                                            <span class="badge {{ $peminjaman->status == 'dikembalikan' ? 'bg-success' : ($peminjaman->status == 'disetujui' ? 'bg-primary' : ($peminjaman->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark')) }}">
                                                {{ ucfirst($peminjaman->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $peminjaman->no_telp }}</td>
                                        <td>
                                            <a href="{{ route('list.peminjam.detail', $peminjaman->id) }}" class="btn btn-sm btn-info text-white">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $peminjamans->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            Tidak ada data peminjam yang ditemukan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 