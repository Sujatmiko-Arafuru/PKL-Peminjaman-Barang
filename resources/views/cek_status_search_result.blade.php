@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-search me-2"></i>Hasil Pencarian</h1>
            
            @if(session('kode_peminjaman'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-receipt me-2"></i>
                    <strong>Kode Peminjaman Anda:</strong> 
                    <span class="badge bg-dark ms-2">{{ session('kode_peminjaman') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Hasil pencarian untuk:</strong>
                @if($request->filled('kode_peminjaman'))
                    <span class="badge bg-primary me-2">Kode: {{ $request->kode_peminjaman }}</span>
                @endif
                @if($request->filled('nama_kegiatan'))
                    <span class="badge bg-success me-2">Kegiatan: {{ $request->nama_kegiatan }}</span>
                @endif
                @if($request->filled('nama_peminjam'))
                    <span class="badge bg-info me-2">Peminjam: {{ $request->nama_peminjam }}</span>
                @endif
                @if($request->filled('no_telp'))
                    <span class="badge bg-warning me-2">Telepon: {{ $request->no_telp }}</span>
                @endif
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
                    Tidak ditemukan peminjaman dengan kriteria pencarian yang diberikan.
                </div>
            @endif
            
            <div class="text-center mt-4">
                <a href="{{ route('cekStatus.form') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Pencarian
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 