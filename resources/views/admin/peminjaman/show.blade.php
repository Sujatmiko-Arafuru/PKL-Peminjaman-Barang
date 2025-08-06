@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-eye me-2"></i>Detail Peminjaman</h2>
    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke List Peminjaman
    </a>
</div>

<div class="row">
    <!-- Data Peminjam -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Data Peminjam</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        @if($peminjaman->foto_peminjam)
                            <img src="{{ asset('storage/' . $peminjaman->foto_peminjam) }}" alt="Foto Peminjam" class="img-fluid rounded shadow-sm" style="max-width: 180px; max-height: 180px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 180px; height: 180px;">
                                <i class="bi bi-person text-muted" style="font-size: 3.5rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                                <p class="mb-0 fw-semibold">{{ $peminjaman->nama }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">No. Telepon</label>
                                <p class="mb-0 fw-semibold">{{ $peminjaman->no_telp }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Unit/Jurusan</label>
                                <p class="mb-0 fw-semibold">{{ $peminjaman->unit }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Nama Kegiatan</label>
                                <p class="mb-0 fw-semibold">{{ $peminjaman->nama_kegiatan }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Tanggal Peminjaman</label>
                                <p class="mb-0 fw-semibold">{{ $peminjaman->tanggal_mulai }} s/d {{ $peminjaman->tanggal_selesai }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Kode Peminjaman</label>
                                <p class="mb-0 fw-semibold text-primary">{{ $peminjaman->kode_peminjaman }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Tanggal Pengajuan</label>
                                <p class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Status</label>
                                <div>
                                    @if($peminjaman->status == 'menunggu')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i>Menunggu
                                        </span>
                                    @elseif($peminjaman->status == 'disetujui')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Disetujui
                                        </span>
                                    @elseif($peminjaman->status == 'ditolak')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Ditolak
                                        </span>
                                    @elseif($peminjaman->status == 'pengembalian_diajukan')
                                        <span class="badge bg-info">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Pengembalian Diajukan
                                        </span>
                                    @elseif($peminjaman->status == 'dikembalikan')
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-check2-all me-1"></i>Dikembalikan
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($peminjaman->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Lampiran Bukti</label>
                                <div>
                                    @if($peminjaman->bukti)
                                        <a href="{{ asset('storage/' . $peminjaman->bukti) }}" target="_blank" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-file-earmark me-1"></i>Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Barang yang Dipinjam -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Barang yang Dipinjam</h5>
            </div>
            <div class="card-body">
                @if($peminjaman->details->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman->details as $detail)
                                    <tr>
                                        <td>{{ $detail->barang->nama ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $detail->jumlah }}</span>
                                        </td>
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

<!-- Action Buttons -->
@if($peminjaman->status == 'menunggu')
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="card-title text-primary mb-3"><i class="bi bi-gear me-2"></i>Aksi Peminjaman</h6>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.peminjaman.approve', $peminjaman->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-success" onclick="return confirm('Approve peminjaman ini?')">
                    <i class="bi bi-check-circle me-2"></i>Approve Peminjaman
                </button>
            </form>
            <form action="{{ route('admin.peminjaman.reject', $peminjaman->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-danger" onclick="return confirm('Tolak peminjaman ini?')">
                    <i class="bi bi-x-circle me-2"></i>Reject Peminjaman
                </button>
            </form>
        </div>
    </div>
</div>
@endif

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 8px;
    overflow: hidden;
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    padding: 1rem 1.5rem;
}

.badge {
    font-size: 0.75em;
    padding: 0.5em 0.75em;
    font-weight: 500;
}

.table-sm td, .table-sm th {
    padding: 0.5rem;
    font-size: 0.875rem;
}

.form-label {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection 