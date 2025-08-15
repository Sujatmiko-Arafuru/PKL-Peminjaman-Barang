@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-arrow-clockwise me-2"></i>Kelola Pengembalian
            </h2>
            <p class="text-muted mb-0">Input data pengembalian dan approve pengembalian barang</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Form Input Pengembalian Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="bi bi-keyboard me-2"></i>Form Input Pengembalian
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.pengembalian.input-kode') }}" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label class="form-label text-muted small">Kode Peminjaman</label>
                    <input type="text" name="kode_peminjaman" class="form-control form-control-sm" 
                           placeholder="Contoh: ANG-20250814-0001">
                    <small class="text-muted">Opsional - bisa dikosongkan</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control form-control-sm" 
                           placeholder="Nama lengkap peminjam">
                    <small class="text-muted">Opsional - bisa dikosongkan</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="form-control form-control-sm" 
                           placeholder="Nama kegiatan yang dilakukan">
                    <small class="text-muted">Opsional - bisa dikosongkan</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small">No HP</label>
                    <input type="text" name="no_telp" class="form-control form-control-sm" 
                           placeholder="Nomor HP peminjam">
                    <small class="text-muted">Opsional - bisa dikosongkan</small>
                </div>
                <div class="col-12">
                    <hr class="my-3">
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="bi bi-search me-2"></i>Cari Data Peminjaman
                    </button>
                    <small class="text-muted ms-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Admin bisa mengisi salah satu atau lebih form untuk memastikan data yang benar
                    </small>
                </div>
            </form>
        </div>
    </div>

    <!-- Hasil Pencarian Section -->
    @if(isset($peminjaman) && $peminjaman)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0">
                <i class="bi bi-check-circle me-2"></i>Data Peminjaman Ditemukan
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-person me-2"></i>Informasi Peminjam
                    </h6>
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-bold" style="width: 40%">Kode Peminjaman:</td>
                            <td><span class="badge bg-dark">{{ $peminjaman->kode_peminjaman }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Nama:</td>
                            <td><strong>{{ $peminjaman->nama }}</strong></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Unit:</td>
                            <td>{{ $peminjaman->unit }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">No. HP:</td>
                            <td>{{ $peminjaman->no_telp }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status:</td>
                            <td>
                                @if($peminjaman->status == 'disetujui')
                                    <span class="badge bg-success rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>Disetujui
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ ucfirst($peminjaman->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-calendar-event me-2"></i>Informasi Kegiatan
                    </h6>
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-bold" style="width: 40%">Nama Kegiatan:</td>
                            <td>{{ $peminjaman->nama_kegiatan }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Mulai:</td>
                            <td>{{ format_tanggal($peminjaman->tanggal_mulai) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Selesai:</td>
                            <td>{{ format_tanggal($peminjaman->tanggal_selesai) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Pengajuan:</td>
                            <td>{{ format_tanggal($peminjaman->created_at, true) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center">
                <a href="{{ route('admin.pengembalian.show', $peminjaman->id) }}" 
                   class="btn btn-primary shadow-sm me-2">
                    <i class="bi bi-eye me-2"></i>Lihat Detail & Centang Barang
                </a>
                <form action="{{ route('admin.pengembalian.approve', $peminjaman->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success shadow-sm" 
                            onclick="return confirm('Approve pengembalian ini? Pastikan semua barang sudah dicek!')">
                        <i class="bi bi-check-lg me-2"></i>Approve Pengembalian
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Informasi Tambahan -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>Informasi Pengembalian
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-gear me-2"></i>Cara Kerja Sistem
                    </h6>
                    <ol class="mb-0">
                        <li>Admin mengisi form pencarian (bisa salah satu atau lebih)</li>
                        <li>Sistem mencari data peminjaman yang sesuai</li>
                        <li>Admin melihat detail dan centang barang yang dikembalikan</li>
                        <li>Admin approve pengembalian setelah verifikasi</li>
                        <li>Mahasiswa bisa lihat status di menu "List Peminjam"</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-eye me-2"></i>Status yang Dapat Dilihat Mahasiswa
                    </h6>
                    <ul class="mb-0">
                        <li><span class="badge bg-warning text-dark">Pengembalian Diajukan</span> - Sedang diproses admin</li>
                        <li><span class="badge bg-success">Dikembalikan</span> - Sudah selesai dan diverifikasi</li>
                        <li><span class="badge bg-danger">Pengembalian Ditolak</span> - Ditolak oleh admin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 0.75rem;
}

.table th {
    font-size: 0.875rem;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
}

.btn {
    border-radius: 0.5rem;
}

.pagination {
    --bs-pagination-border-radius: 0.5rem;
}

/* Form styling */
.form-control:focus, .form-select:focus {
    border-color: #20B2AA;
    box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
}

/* Alert styling */
.alert {
    border-radius: 0.5rem;
    border: none;
}

.alert-success {
    background: linear-gradient(135deg, #2E8B57, #3CB371);
    color: white;
}

.alert-danger {
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
}

.alert-info {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

/* Table improvements */
.table-sm td, .table-sm th {
    padding: 0.5rem;
    border: none;
}

.table-sm tr {
    border-bottom: 1px solid #f1f3f4;
}

/* Badge improvements */
.badge.bg-dark {
    background-color: #343a40 !important;
    color: white !important;
}

.badge.bg-success {
    background-color: #28a745 !important;
    color: white !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.badge.bg-info {
    background-color: #17a2b8 !important;
    color: white !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
    color: white !important;
}

/* Card header improvements */
.card-header {
    border-bottom: none;
}

/* Button improvements */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Info section improvements */
.card-body ul, .card-body ol {
    padding-left: 1.5rem;
}

.card-body li {
    margin-bottom: 0.5rem;
}
</style>
@endsection 