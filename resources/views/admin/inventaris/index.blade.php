@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-box-seam me-2"></i>Inventaris Barang
            </h2>
            <p class="text-muted mb-0">Kelola data barang dan stok inventaris</p>
        </div>
        <div>
            <a href="{{ route('admin.inventaris.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Barang
            </a>
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

    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-boxes text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-primary fw-semibold">Total Barang</h6>
                            <p class="mb-0 text-muted small">{{ $barangs->count() }} item</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-success fw-semibold">Stok Tersedia</h6>
                            <p class="mb-0 text-muted small">{{ $barangs->sum('stok_tersedia') }} unit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-arrow-repeat text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-warning fw-semibold">Stok Dipinjam</h6>
                            <p class="mb-0 text-muted small">{{ $barangs->sum('stok_dipinjam') }} unit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-graph-up text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-info fw-semibold">Total Stok</h6>
                            <p class="mb-0 text-muted small">{{ $barangs->sum('stok') }} unit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-table me-2"></i>Data Inventaris Barang
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Foto</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Nama Barang</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Stok Tersedia</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Stok Dipinjam</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Status</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Deskripsi</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $barang)
                        <tr class="border-bottom">
                            <td class="px-3 py-3">
                                @if($barang->hasPhotos())
                                    <div class="position-relative">
                                        <img src="{{ Storage::url($barang->main_photo) }}" 
                                             alt="{{ $barang->nama }}" 
                                             class="rounded" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        @if($barang->photo_count > 1)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" 
                                                  style="font-size: 0.6rem; transform: translate(-50%, -50%);">
                                                +{{ $barang->photo_count - 1 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-box-seam text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <div class="fw-semibold text-dark">{{ $barang->nama }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge bg-success rounded-pill fs-6">{{ $barang->stok_tersedia }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge bg-warning text-dark rounded-pill fs-6">{{ $barang->stok_dipinjam }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge rounded-pill fs-6
                                    {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($barang->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="text-muted" title="{{ $barang->deskripsi }}">
                                    {{ Str::limit($barang->deskripsi, 40) }}
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.inventaris.show', $barang->id) }}" 
                                       class="btn btn-sm btn-outline-info shadow-sm">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                    <a href="{{ route('admin.inventaris.edit', $barang->id) }}" 
                                       class="btn btn-sm btn-outline-primary shadow-sm">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.inventaris.destroy', $barang->id) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin hapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger shadow-sm">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($barangs->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Navigasi halaman inventaris barang">
            <ul class="pagination pagination-lg">
                {{-- Previous Page Link --}}
                @if ($barangs->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="bi bi-chevron-left"></i>
                            <span class="d-none d-sm-inline">Sebelumnya</span>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $barangs->previousPageUrl() }}" rel="prev">
                            <i class="bi bi-chevron-left"></i>
                            <span class="d-none d-sm-inline">Sebelumnya</span>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($barangs->getUrlRange(1, $barangs->lastPage()) as $page => $url)
                    @if ($page == $barangs->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($barangs->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $barangs->nextPageUrl() }}" rel="next">
                            <span class="d-none d-sm-inline">Selanjutnya</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <span class="d-none d-sm-inline">Selanjutnya</span>
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <!-- Page Info -->
    <div class="text-center text-muted mb-3">
        <small>
            Menampilkan {{ $barangs->firstItem() ?? 0 }} - {{ $barangs->lastItem() ?? 0 }} 
            dari {{ $barangs->total() }} barang 
            (Halaman {{ $barangs->currentPage() }} dari {{ $barangs->lastPage() }})
        </small>
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

.table img {
    border: 1px solid #dee2e6;
}

/* Pagination Styling */
.pagination {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
}

.pagination .page-link {
    border: none;
    color: #0d6efd;
    padding: 12px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background-color: #e7f1ff;
    color: #0d6efd;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #f8f9fa;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    border-radius: 0;
}

/* Responsive pagination text */
@media (max-width: 576px) {
    .pagination .page-link {
        padding: 10px 12px;
        font-size: 0.9rem;
    }
}

/* Page info styling */
.text-muted small {
    font-size: 0.875rem;
    font-weight: 500;
}
</style>
@endsection 