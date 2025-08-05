@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
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