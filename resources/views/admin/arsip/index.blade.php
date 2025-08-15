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
                    <label class="form-label text-muted small">Cari Nama/Kode</label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari nama/kode..." value="{{ request('search') }}">
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
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">KODE UNIK</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">NAMA</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">NO HP</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">UNIT/JURUSAN</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">KEGIATAN</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">PERIODE</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">TANGGAL PENGAJUAN</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">STATUS</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">BARANG</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">LIHAT DETAIL</th>
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
                                    <div>{{ format_tanggal($p->tanggal_mulai) }}</div>
                                    <div class="text-muted">s/d</div>
                                    <div>{{ format_tanggal($p->tanggal_selesai) }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="small text-muted">
                                    <div>{{ format_tanggal($p->created_at) }}</div>
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
                                            {{ Str::limit($detail->barang->nama ?? '-', 20) }} ({{ $detail->jumlah }})
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <button type="button" class="btn btn-sm btn-outline-primary shadow-sm detail-btn" 
                                        data-peminjaman-id="{{ $p->id }}">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </button>
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

<!-- Modal Detail Peminjaman -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="bi bi-info-circle me-2"></i>Detail Peminjaman
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded here -->
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

/* Modal improvements */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: none;
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

/* Table improvements */
.table-hover tbody tr:hover {
    background-color: rgba(32, 178, 170, 0.05);
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table td {
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

.badge.bg-primary {
    background-color: #007bff !important;
    color: white !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
    color: white !important;
}

.badge.bg-secondary {
    background-color: #6c757d !important;
    color: white !important;
}

.badge.bg-light {
    background-color: #f8f9fa !important;
    color: #212529 !important;
}

.badge.bg-info {
    background-color: #17a2b8 !important;
    color: white !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailButtons = document.querySelectorAll('.detail-btn');
    
    detailButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const peminjamanId = this.getAttribute('data-peminjaman-id');
            showDetailModal(peminjamanId);
        });
    });
    
    function showDetailModal(peminjamanId) {
        const row = document.querySelector(`[data-peminjaman-id="${peminjamanId}"]`).closest('tr');
        const cells = row.querySelectorAll('td');
        
        const kodePeminjaman = cells[0].querySelector('.badge').textContent;
        const nama = cells[1].querySelector('.fw-semibold').textContent;
        const noTelp = cells[2].textContent;
        const unit = cells[3].querySelector('.badge').textContent;
        const kegiatan = cells[4].querySelector('.fw-medium').getAttribute('title') || cells[4].querySelector('.fw-medium').textContent;
        const periodeMulai = cells[5].querySelector('.small div:first-child').textContent;
        const periodeSelesai = cells[5].querySelector('.small div:last-child').textContent;
        const tanggalPengajuan = cells[6].querySelector('.small div:first-child').textContent;
        const waktuPengajuan = cells[6].querySelector('.small div:last-child').textContent;
        const status = cells[7].querySelector('.badge').textContent;
        
        const barangDetails = [];
        cells[8].querySelectorAll('.badge').forEach(badge => {
            barangDetails.push(badge.textContent);
        });
        
        const modalContent = `
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
                                <small class="text-muted">Kode Peminjaman</small>
                                <div class="fw-semibold text-primary">${kodePeminjaman}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Nama</small>
                                <div class="fw-semibold">${nama}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">No HP</small>
                                <div class="fw-semibold">${noTelp}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Unit/Jurusan</small>
                                <div class="fw-semibold">${unit}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Nama Kegiatan</small>
                                <div class="fw-semibold">${kegiatan}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Periode Peminjaman</small>
                                <div class="fw-semibold">${periodeMulai} - ${periodeSelesai}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Tanggal Pengajuan</small>
                                <div class="fw-semibold">${tanggalPengajuan} ${waktuPengajuan}</div>
                            </div>
                            <div class="mb-0">
                                <small class="text-muted">Status</small>
                                <div>${cells[7].innerHTML}</div>
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
                            ${barangDetails.length > 0 ? `
                                <div class="list-group list-group-flush">
                                    ${barangDetails.map(barang => `
                                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                            <div>
                                                <div class="fw-semibold">${barang.split(' (')[0]}</div>
                                                <small class="text-muted">Jumlah: ${barang.match(/\((\d+)\)/)?.[1] || '-'}</small>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            ` : `
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-box-seam fs-1"></i>
                                    <p class="mb-0 mt-2">Tidak ada barang</p>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('modalBody').innerHTML = modalContent;
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    }
    
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            const modal = bootstrap.Modal.getInstance(this);
            modal.hide();
        }
    });
});
</script>
@endsection 