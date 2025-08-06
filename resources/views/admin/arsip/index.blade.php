@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-archive me-2"></i>Arsip Peminjaman & Pengembalian
            </h2>
            <p class="text-muted mb-0">Kelola dan lihat riwayat peminjaman barang</p>
        </div>
        <div>
            <a href="{{ route('admin.arsip.export.pdf') }}{{ count(request()->all()) ? '?' . http_build_query(request()->all()) : '' }}" 
               class="btn btn-danger shadow-sm">
                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-funnel me-2"></i>Filter Data
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small">Cari Nama</label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari nama user..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                        <option value="pengembalian_diajukan" {{ request('status')=='pengembalian_diajukan'?'selected':'' }}>Pengembalian Diajukan</option>
                        <option value="dikembalikan" {{ request('status')=='dikembalikan'?'selected':'' }}>Dikembalikan</option>
                        <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                        <option value="pengembalian ditolak" {{ request('status')=='pengembalian ditolak'?'selected':'' }}>Pengembalian Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm" 
                           value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control form-control-sm" 
                           value="{{ request('tanggal_selesai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Urutan</label>
                    <select name="urut" class="form-select form-select-sm">
                        <option value="terbaru" {{ request('urut')=='terbaru'?'selected':'' }}>Terbaru</option>
                        <option value="terlama" {{ request('urut')=='terlama'?'selected':'' }}>Terlama</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label text-muted small">&nbsp;</label>
                    <button class="btn btn-primary btn-sm w-100 shadow-sm">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-star-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-primary fw-semibold">Barang Terlaris</h6>
                            <p class="mb-0 text-muted small">
                                {{ $terlaris ? $terlaris->nama . ' (' . ($terlaris->details_count ?? 0) . 'x)' : 'Belum ada data' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-box-seam text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary fw-semibold">Barang Tidak Pernah Dipinjam</h6>
                            <p class="mb-0 text-muted small">
                                @if($tidakPernah && count($tidakPernah) > 0)
                                    {{ count($tidakPernah) }} item
                                @else
                                    Semua barang sudah dipinjam
                                @endif
                            </p>
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
                <i class="bi bi-table me-2"></i>Data Arsip Peminjaman
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
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">Barang</th>
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
                                <div class="small text-muted">
                                    <div>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }}</div>
                                    <div class="text-muted">s/d</div>
                                    <div>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="small text-muted">
                                    <div>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</div>
                                    <div class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge rounded-pill
                                    @if($p->status == 'dikembalikan') bg-success
                                    @elseif($p->status == 'disetujui') bg-primary
                                    @elseif($p->status == 'pengembalian_diajukan') bg-warning text-dark
                                    @elseif($p->status == 'ditolak' || $p->status == 'pengembalian ditolak') bg-danger
                                    @else bg-secondary
                                    @endif
                                ">
                                    @if($p->status == 'pengembalian_diajukan')
                                        Pengembalian Diajukan
                                    @elseif($p->status == 'pengembalian ditolak')
                                        Pengembalian Ditolak
                                    @else
                                        {{ ucfirst($p->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($p->details as $detail)
                                        <span class="badge bg-info bg-opacity-75 text-dark rounded-pill">
                                            {{ $detail->barang->nama ?? '-' }} ({{ $detail->jumlah }})
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" 
                                        data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </button>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1" 
                                     aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="detailModalLabel{{ $p->id }}">
                                                    <i class="bi bi-info-circle me-2"></i>Detail Peminjaman
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" 
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0 text-primary">
                                                                    <i class="bi bi-person me-2"></i>Data Peminjam
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Nama</small>
                                                                    <div class="fw-semibold">{{ $p->nama }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">No HP</small>
                                                                    <div class="fw-semibold">{{ $p->no_telp }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Unit/Jurusan</small>
                                                                    <div class="fw-semibold">{{ $p->unit }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Nama Kegiatan</small>
                                                                    <div class="fw-semibold">{{ $p->nama_kegiatan }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Tujuan</small>
                                                                    <div class="fw-semibold">{{ $p->tujuan }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Periode Peminjaman</small>
                                                                    <div class="fw-semibold">
                                                                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }} - 
                                                                        {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Kode Peminjaman</small>
                                                                    <div class="fw-semibold text-primary">{{ $p->kode_peminjaman }}</div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Lampiran</small>
                                                                    <div>
                                                                        @if($p->bukti)
                                                                            <a href="{{ asset('storage/' . $p->bukti) }}" 
                                                                               target="_blank" class="btn btn-sm btn-outline-info">
                                                                                <i class="bi bi-file-earmark me-1"></i>Lihat Bukti
                                                                            </a>
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="mb-0">
                                                                    <small class="text-muted">Status</small>
                                                                    <div>
                                                                        <span class="badge rounded-pill
                                                                            @if($p->status == 'dikembalikan') bg-success
                                                                            @elseif($p->status == 'disetujui') bg-primary
                                                                            @elseif($p->status == 'ditolak') bg-danger
                                                                            @else bg-warning text-dark
                                                                            @endif
                                                                        ">{{ ucfirst($p->status) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0 text-primary">
                                                                    <i class="bi bi-box-seam me-2"></i>Barang yang Dipinjam
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                @if($p->details->count() > 0)
                                                                    <div class="list-group list-group-flush">
                                                                        @foreach($p->details as $detail)
                                                                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                                                            <div>
                                                                                <div class="fw-semibold">{{ $detail->barang->nama ?? '-' }}</div>
                                                                                <small class="text-muted">ID: {{ $detail->barang->id ?? '-' }}</small>
                                                                            </div>
                                                                            <span class="badge bg-primary rounded-pill">{{ $detail->jumlah }}</span>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <div class="text-center text-muted py-3">
                                                                        <i class="bi bi-box-seam fs-1"></i>
                                                                        <p class="mb-0 mt-2">Tidak ada barang</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p class="mb-0 mt-2">Tidak ada data arsip</p>
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

.modal-content {
    border-radius: 1rem;
}

.list-group-item {
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

.pagination {
    --bs-pagination-border-radius: 0.5rem;
}
</style>
@endsection 