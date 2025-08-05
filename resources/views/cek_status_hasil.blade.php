@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-search me-2"></i>Hasil Pencarian Status</h1>
            
            @if($peminjaman)
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i> Data peminjaman ditemukan.
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Data Peminjam</h5>
                            </div>
                            <div class="card-body">
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
                                        <span><strong>Tujuan:</strong></span>
                                        <span>{{ $peminjaman->tujuan }}</span>
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
            @else
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i> Data peminjaman tidak ditemukan. Pastikan kode peminjaman benar.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 