@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-arrow-clockwise me-2"></i>Kelola Pengembalian
            </h2>
            <p class="text-muted mb-0">Kelola dan monitor pengembalian barang</p>
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
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-muted small">Cari Nama/No HP</label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari nama/no hp..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small">Urutan</label>
                    <select name="urut" class="form-select form-select-sm">
                        <option value="terlama" {{ request('urut')=='terlama'?'selected':'' }}>Terlama</option>
                        <option value="terbaru" {{ request('urut')=='terbaru'?'selected':'' }}>Terbaru</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">&nbsp;</label>
                    <button class="btn btn-primary btn-sm w-100 shadow-sm">
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
                <i class="bi bi-table me-2"></i>Daftar Pengembalian
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kode Unik</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Nama</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">No HP</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Unit/Jurusan</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kegiatan</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Periode</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Tanggal Pengajuan</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Status</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                        <tr class="border-bottom">
                            <td class="px-3 py-3">
                                <span class="badge bg-dark">{{ $p->kode_peminjaman }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="fw-semibold text-dark">{{ $p->nama }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="text-muted">{{ $p->no_telp }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge bg-light text-dark">{{ $p->unit }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="fw-medium" title="{{ $p->nama_kegiatan }}">
                                    {{ Str::limit($p->nama_kegiatan, 25) }}
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
                                <div class="mb-2">
                                    <small class="text-muted">Tanggal Pengajuan</small>
                                    <div>{{ format_tanggal($p->created_at) }}</div>
                                    <div class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i>{{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.pengembalian.show', $p->id) }}" 
                                       class="btn btn-sm btn-outline-info shadow-sm">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                    <form action="{{ route('admin.pengembalian.approve', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success shadow-sm" 
                                                onclick="return confirm('Approve pengembalian ini?')">
                                            <i class="bi bi-check-lg me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pengembalian.reject', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger shadow-sm" 
                                                onclick="return confirm('Tolak pengembalian ini?')">
                                            <i class="bi bi-x-lg me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-check-circle fs-1 text-success"></i>
                                    <p class="mb-0 mt-2">Tidak ada pengembalian menunggu approve</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($peminjamans->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $peminjamans->withQueryString()->links() }}
    </div>
    @endif
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

.btn-sm {
    font-size: 0.875rem;
}

.pagination {
    --bs-pagination-border-radius: 0.5rem;
}
</style>
@endsection 