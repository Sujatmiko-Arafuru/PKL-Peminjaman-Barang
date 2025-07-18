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
            <h1 class="dashboard-title mb-3"><i class="bi bi-arrow-repeat me-2"></i>Pengembalian Barang</h1>
            
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-list"></i> Cari Berdasarkan Nama Kegiatan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('cekStatus.search') }}" method="GET" class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Kegiatan</label>
                                    <input type="text" name="nama_kegiatan" class="form-control" placeholder="Masukkan nama kegiatan..." required>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i> Cek Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Hapus box informasi pengembalian di bawah form --}}
        </div>
    </div>
</div>
@endsection 