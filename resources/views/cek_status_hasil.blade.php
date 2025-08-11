@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-search me-2"></i>Hasil Pencarian Status</h1>
            
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
            
            @if($peminjaman)
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i> Data peminjaman ditemukan.
                    <br><small class="text-muted">Format kode: NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)</small>
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
                                        <span><strong>Kode Peminjaman:</strong></span>
                                        <span><span class="badge bg-dark">{{ $peminjaman->kode_peminjaman }}</span></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span><strong>Nama:</strong></span>
                                        <span>{{ $peminjaman->nama }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span><strong>No. Telepon:</strong></span>
                                        <span>{{ $peminjaman->no_telp }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span><strong>Jurusan / Ormawa:</strong></span>
                                        <span>{{ $peminjaman->unit }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span><strong>Nama Kegiatan:</strong></span>
                                        <span>{{ $peminjaman->nama_kegiatan }}</span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between">
                                        <span><strong>Tanggal Pinjam:</strong></span>
                                        <span>{{ format_tanggal($peminjaman->tanggal_mulai) }} s/d {{ format_tanggal($peminjaman->tanggal_selesai) }}</span>
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
                
                <div class="text-center mt-4">
                    <a href="{{ route('cekStatus.detail', $peminjaman->id) }}" class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>Lihat Detail Lengkap
                    </a>
                    <a href="{{ route('cekStatus.form') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Pencarian
                    </a>
                </div>
            @else
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i> Data peminjaman tidak ditemukan. Pastikan kode peminjaman benar.
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('cekStatus.form') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Pencarian
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 