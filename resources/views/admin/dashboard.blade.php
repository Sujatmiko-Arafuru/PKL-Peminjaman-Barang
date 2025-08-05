@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin SarPras
            </h2>
            <p class="text-muted mb-0">Selamat datang! Kelola sistem peminjaman barang dengan mudah</p>
        </div>
        <div>
            <span class="badge bg-success fs-6">
                <i class="bi bi-clock me-1"></i>{{ now()->format('d M Y, H:i') }}
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-hourglass-split text-warning fs-2"></i>
                    </div>
                    <div class="fw-bold fs-3 text-dark mb-1">{{ $menungguApprove }}</div>
                    <div class="text-muted small">Menunggu Approve Peminjaman</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-people text-primary fs-2"></i>
                    </div>
                    <div class="fw-bold fs-3 text-dark mb-1">{{ $totalPengguna }}</div>
                    <div class="text-muted small">Total Peminjam</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-arrow-repeat text-info fs-2"></i>
                    </div>
                    <div class="fw-bold fs-3 text-dark mb-1">{{ $menungguPengembalian }}</div>
                    <div class="text-muted small">Menunggu Approve Pengembalian</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-box-seam text-success fs-2"></i>
                    </div>
                    <div class="fw-bold fs-3 text-dark mb-1">{{ $totalBarang }}</div>
                    <div class="text-muted small">Total Barang</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Approve Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-lightning me-2"></i>Quick Approve Peminjaman
            </h6>
        </div>
        <div class="card-body p-0">
            @if($quickApprove->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Nama</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">No HP</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Unit/Jurusan</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kegiatan</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Periode</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Status</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quickApprove as $p)
                        <tr class="border-bottom">
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
                                <div class="small text-muted">
                                    <div>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }}</div>
                                    <div class="text-muted">s/d</div>
                                    <div>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge bg-warning text-dark rounded-pill">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.peminjaman.show', $p->id) }}" 
                                       class="btn btn-sm btn-outline-info shadow-sm">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
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
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                    <p class="mb-0 mt-2">Belum ada peminjaman yang menunggu approve</p>
                </div>
            </div>
            @endif
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

.btn-sm {
    font-size: 0.875rem;
}
</style>
@endsection 