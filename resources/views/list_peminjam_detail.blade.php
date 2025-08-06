@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-person me-2"></i>Detail Peminjam</h1>
            
            @if(session('kode_peminjaman'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-receipt me-2"></i>
                    <strong>Kode Peminjaman Anda:</strong> 
                    <span class="badge bg-dark ms-2">{{ session('kode_peminjaman') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Informasi Peminjam</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Kode Peminjaman:</strong></span>
                                    <span><span class="badge bg-dark">{{ $peminjaman->kode_peminjaman }}</span></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Nama:</strong></span>
                                    <span>{{ $peminjaman->nama }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Nama Kegiatan:</strong></span>
                                    <span>{{ $peminjaman->nama_kegiatan }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Tanggal Kegiatan:</strong></span>
                                    <span>{{ $peminjaman->tanggal_mulai }} s/d {{ $peminjaman->tanggal_selesai }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Status:</strong></span>
                                    <span class="badge {{ $peminjaman->status == 'dikembalikan' ? 'bg-success' : ($peminjaman->status == 'disetujui' ? 'bg-primary' : ($peminjaman->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark')) }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>No. Handphone:</strong></span>
                                    <span>{{ $peminjaman->no_telp }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Barang yang Dipinjam</h5>
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
            
            <div class="text-center mt-4">
                <a href="{{ route('list.peminjam') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke List Peminjam
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 