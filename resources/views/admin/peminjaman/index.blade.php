@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people me-2"></i>Kelola Peminjaman</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Filter Section -->
<form method="GET" class="row g-2 mb-4" id="filterForm">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Cari nama kegiatan..." value="{{ request('search') }}" id="searchInput">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select" id="statusSelect">
            <option value="semua" {{ request('status')=='semua'?'selected':'' }}>Semua Status</option>
            <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
            <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
            <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
            <option value="pengembalian_diajukan" {{ request('status')=='pengembalian_diajukan'?'selected':'' }}>Pengembalian Diajukan</option>
            <option value="dikembalikan" {{ request('status')=='dikembalikan'?'selected':'' }}>Dikembalikan</option>
            <option value="pengembalian ditolak" {{ request('status')=='pengembalian ditolak'?'selected':'' }}>Pengembalian Ditolak</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="urut" class="form-select" id="sortSelect">
            <option value="terbaru" {{ request('urut')=='terbaru'?'selected':'' }}>Urut Terbaru</option>
            <option value="terlama" {{ request('urut')=='terlama'?'selected':'' }}>Urut Terlama</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-filter" id="filterBtn">
            <span class="btn-text">
                <i class="bi bi-funnel"></i> Filter
            </span>
            <span class="btn-loading d-none">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Loading...
            </span>
        </button>
    </div>
</form>

<!-- Main Table -->
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Peminjam</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">Nama</th>
                        <th>Kegiatan & Unit</th>
                        <th>Tanggal Kegiatan</th>
                        <th>Status</th>
                        <th>No. HP</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($peminjamans as $p)
                    <tr>
                        <td class="px-3">
                            <div class="fw-bold">{{ $p->nama }}</div>
                            <small class="text-muted">{{ $p->unit }}</small>
                        </td>
                        <td>
                            <div class="fw-medium">{{ Str::limit($p->nama_kegiatan, 50) }}</div>
                            <small class="text-muted">{{ $p->unit }}</small>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $p->tanggal_mulai }}</div>
                            <div class="text-muted">s/d {{ $p->tanggal_selesai }}</div>
                        </td>
                        <td>
                            @if($p->status == 'menunggu')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock me-1"></i>Menunggu
                                </span>
                            @elseif($p->status == 'disetujui')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Disetujui
                                </span>
                            @elseif($p->status == 'ditolak')
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                </span>
                            @elseif($p->status == 'pengembalian_diajukan')
                                <span class="badge bg-info">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Pengembalian Diajukan
                                </span>
                            @elseif($p->status == 'dikembalikan')
                                <span class="badge bg-secondary">
                                    <i class="bi bi-check2-all me-1"></i>Dikembalikan
                                </span>
                            @elseif($p->status == 'pengembalian ditolak')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Pengembalian Ditolak
                                </span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-medium">{{ $p->no_telp }}</div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.peminjaman.show', $p->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            @if($p->status == 'menunggu')
                                <form action="{{ route('admin.peminjaman.approve', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm" onclick="return confirm('Approve peminjaman?')">
                                        <i class="bi bi-check me-1"></i>Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.peminjaman.reject', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak peminjaman?')">
                                        <i class="bi bi-x me-1"></i>Reject
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-4"></i>
                                <p class="mt-2">Tidak ada data peminjaman</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusSelect = document.getElementById('statusSelect');
    const sortSelect = document.getElementById('sortSelect');
    const filterBtn = document.getElementById('filterBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const tableBody = document.getElementById('tableBody');

    let searchTimeout;
    let isLoading = false;

    // Initial hide
    hideLoading();

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (!isLoading) { showLoading(); filterForm.submit(); }
        }, 500);
    });

    statusSelect.addEventListener('change', function() {
        if (!isLoading) { showLoading(); filterForm.submit(); }
    });

    sortSelect.addEventListener('change', function() {
        if (!isLoading) { showLoading(); filterForm.submit(); }
    });

    filterForm.addEventListener('submit', function(e) {
        if (!isLoading) { showLoading(); }
    });

    function showLoading() {
        isLoading = true;
        const btnText = filterBtn.querySelector('.btn-text');
        const btnLoading = filterBtn.querySelector('.btn-loading');
        if (btnText && btnLoading) { btnText.classList.add('d-none'); btnLoading.classList.remove('d-none'); }
        filterBtn.disabled = true;
        if (loadingOverlay) { loadingOverlay.classList.remove('d-none'); }
        if (tableBody) { tableBody.style.opacity = '0.5'; tableBody.style.transition = 'opacity 0.3s'; }
    }

    function hideLoading() {
        isLoading = false;
        const btnText = filterBtn.querySelector('.btn-text');
        const btnLoading = filterBtn.querySelector('.btn-loading');
        if (btnText && btnLoading) { btnText.classList.remove('d-none'); btnLoading.classList.add('d-none'); }
        filterBtn.disabled = false;
        if (loadingOverlay) { loadingOverlay.classList.add('d-none'); }
        if (tableBody) { tableBody.style.opacity = '1'; }
    }

    window.addEventListener('load', function() { 
        setTimeout(hideLoading, 100); 
    });
    setTimeout(hideLoading, 100);
});
</script>

<style>
/* Table styling */
.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    vertical-align: middle;
}

/* Badge styling */
.badge {
    font-size: 0.75em;
    padding: 0.5em 0.75em;
    font-weight: 500;
}

/* Card styling */
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

.card-footer {
    padding: 0.5rem 1.5rem;
    background-color: #f8f9fa;
}

/* Filter button styling */
.btn-filter {
    padding: 8px 16px !important;
    font-size: 14px !important;
    border-radius: 4px !important;
    height: 38px !important;
    line-height: 1.2 !important;
    min-width: 80px !important;
    max-width: 120px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 1px solid #0d6efd !important;
    background-color: #0d6efd !important;
    color: white !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    font-weight: 400 !important;
    box-shadow: none !important;
}

.btn-filter:hover {
    background-color: #0b5ed7 !important;
    border-color: #0a58ca !important;
    color: white !important;
}

.btn-filter:focus {
    background-color: #0b5ed7 !important;
    border-color: #0a58ca !important;
    color: white !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

.btn-filter:active {
    background-color: #0a58ca !important;
    border-color: #0a53be !important;
    color: white !important;
}

.btn-filter .btn-text,
.btn-filter .btn-loading {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 4px !important;
    font-size: 14px !important;
    line-height: 1.2 !important;
}

.btn-filter .spinner-border-sm {
    width: 14px !important;
    height: 14px !important;
}

/* Form control styling */
.form-control,
.form-select {
    height: 38px !important;
    font-size: 14px !important;
    border-radius: 4px !important;
    padding: 8px 12px !important;
    line-height: 1.2 !important;
}

/* Button styling */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 6px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-footer {
        padding: 0.25rem 1rem;
    }
    
    .card-footer .d-flex {
        flex-direction: column;
        gap: 6px;
        text-align: center;
    }
    
    /* Mobile filter button */
    .btn-filter {
        width: 100% !important;
        margin-top: 8px !important;
        max-width: none !important;
    }
}

/* Loading animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.table-hover tbody tr {
    animation: fadeIn 0.3s ease-in-out;
}

/* Focus states for accessibility */
.btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    outline: none;
}
</style>
@endsection 