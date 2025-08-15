@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-people me-2"></i>Kelola Peminjaman
            </h2>
            <p class="text-muted mb-0">Kelola dan monitor semua peminjaman barang</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-funnel me-2"></i>Filter Data
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3" id="filterForm">
                <div class="col-md-4">
                    <label class="form-label text-muted small">Cari Kegiatan</label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari nama kegiatan..." value="{{ request('search') }}" id="searchInput">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small">Status</label>
                    <select name="status" class="form-select form-select-sm" id="statusSelect">
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
                    <label class="form-label text-muted small">Urutan</label>
                    <select name="urut" class="form-select form-select-sm" id="sortSelect">
                        <option value="terbaru" {{ request('urut')=='terbaru'?'selected':'' }}>Terbaru</option>
                        <option value="terlama" {{ request('urut')=='terlama'?'selected':'' }}>Terlama</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm" id="filterBtn">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-table me-2"></i>Daftar Peminjaman
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kode Unik</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Nama & Unit</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kegiatan</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Periode</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Tanggal Pengajuan</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Status</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">No. HP</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($peminjamans as $p)
                        <tr class="border-bottom">
                            <td class="px-3 py-3">
                                <span class="badge bg-dark">{{ $p->kode_peminjaman }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="fw-semibold text-dark">{{ $p->nama }}</div>
                                <small class="text-muted">{{ $p->unit }}</small>
                            </td>
                            <td class="px-3 py-3">
                                <div class="fw-medium" title="{{ $p->nama_kegiatan }}">
                                    {{ Str::limit($p->nama_kegiatan, 50) }}
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="mb-2">
                                    <small class="text-muted">Tanggal Mulai</small>
                                    <div>{{ format_tanggal($p->tanggal_mulai) }}</div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Tanggal Selesai</small>
                                    <div>{{ format_tanggal($p->tanggal_selesai) }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="mb-2">
                                    <small class="text-muted">Tanggal Pengajuan</small>
                                    <div>{{ format_tanggal($p->created_at) }}</div>
                                    <div class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                @if($p->status == 'menunggu')
                                    <span class="badge bg-warning text-dark rounded-pill">
                                        <i class="bi bi-clock me-1"></i>Menunggu
                                    </span>
                                @elseif($p->status == 'disetujui')
                                    <span class="badge bg-success rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>Disetujui
                                    </span>
                                @elseif($p->status == 'ditolak')
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="bi bi-x-circle me-1"></i>Ditolak
                                    </span>
                                @elseif($p->status == 'pengembalian_diajukan')
                                    <span class="badge bg-info rounded-pill">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Pengembalian Diajukan
                                    </span>
                                @elseif($p->status == 'dikembalikan')
                                    <span class="badge bg-secondary rounded-pill">
                                        <i class="bi bi-check2-all me-1"></i>Dikembalikan
                                    </span>
                                @elseif($p->status == 'pengembalian ditolak')
                                    <span class="badge bg-warning text-dark rounded-pill">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Pengembalian Ditolak
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <span class="text-muted">{{ $p->no_telp }}</span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.peminjaman.show', $p->id) }}" 
                                       class="btn btn-sm btn-outline-info shadow-sm">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                    @if($p->status == 'menunggu')
                                        <form action="{{ route('admin.peminjaman.approve', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success shadow-sm" 
                                                    onclick="return confirm('Approve peminjaman ini?')">
                                                <i class="bi bi-check-lg me-1"></i>Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.peminjaman.reject', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-danger shadow-sm" 
                                                    onclick="return confirm('Tolak peminjaman ini?')">
                                                <i class="bi bi-x-lg me-1"></i>Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p class="mb-0 mt-2">Tidak ada data peminjaman</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusSelect = document.getElementById('statusSelect');
    const sortSelect = document.getElementById('sortSelect');

    let searchTimeout;

    // Auto-submit on search input change
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });

    // Auto-submit on select change
    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    sortSelect.addEventListener('change', function() {
        filterForm.submit();
    });
});
</script>

<style>
.card {
    border-radius: 0.75rem;
}

.table-responsive { overflow-x: auto; }
.table { min-width: 1100px; }

.table th {
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
    white-space: nowrap;
}

.table th:nth-child(1), .table td:nth-child(1) { min-width: 150px; }
.table th:nth-child(2), .table td:nth-child(2) { min-width: 200px; }
.table th:nth-child(3), .table td:nth-child(3) { min-width: 220px; }
.table th:nth-child(4), .table td:nth-child(4) { min-width: 220px; }
.table th:nth-child(5), .table td:nth-child(5) { min-width: 200px; }
.table th:nth-child(6), .table td:nth-child(6) { min-width: 140px; }
.table th:nth-child(7), .table td:nth-child(7) { min-width: 160px; }
.table th:nth-child(8), .table td:nth-child(8) { min-width: 140px; text-align:center; }

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    font-size: 0.875rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}
</style>
@endsection 