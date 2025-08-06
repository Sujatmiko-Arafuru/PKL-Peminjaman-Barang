@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-arrow-repeat me-2"></i>Pengembalian Barang</h1>
            
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Format Kode:</strong> NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)
            </div>
            
            @if(session('kode_peminjaman'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-receipt me-2"></i>
                    <strong>Kode Peminjaman Anda:</strong> 
                    <span class="badge bg-dark ms-2">{{ session('kode_peminjaman') }}</span>
                    <br><small class="text-muted">Format: NAMA-TANGGAL-URUTAN</small>
                    <br><small class="text-muted">Contoh: JOH-20241201-0001, SAR-20241201-0002, MIK-20241201-0003, ANA-20241201-0004, DAV-20241201-0005, EMM-20241201-0006, JAM-20241201-0007</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-search me-2"></i> Cari Peminjaman</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('cekStatus.search') }}" method="GET" class="mb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Kode Peminjaman</label>
                                        <input type="text" name="kode_peminjaman" class="form-control" placeholder="Contoh: JOH-20241201-0001">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Nama Kegiatan</label>
                                        <input type="text" name="nama_kegiatan" class="form-control" placeholder="Masukkan nama kegiatan...">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Nama Peminjam</label>
                                        <input type="text" name="nama_peminjam" class="form-control" placeholder="Masukkan nama peminjam...">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">No. Telepon</label>
                                        <input type="text" name="no_telp" class="form-control" placeholder="Contoh: 08123456789">
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Petunjuk:</strong> Anda dapat mencari berdasarkan salah satu atau kombinasi dari kode peminjaman, nama kegiatan, nama peminjam, atau no telepon. Minimal isi salah satu field untuk melakukan pencarian.
                                    <br><small class="text-muted">Format kode: NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)</small>
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