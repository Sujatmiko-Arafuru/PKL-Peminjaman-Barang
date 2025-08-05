@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-info-circle me-2"></i>Detail Peminjaman</h1>
            
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