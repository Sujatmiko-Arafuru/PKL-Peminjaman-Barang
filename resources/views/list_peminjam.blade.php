@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-people me-2"></i>List Peminjam</h1>
            
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Format Kode:</strong> NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)
            </div>
            
            @if(session('kode_peminjaman'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-receipt me-2"></i>
                    <strong>Kode Peminjaman Anda:</strong> 
                    <span class="badge bg-dark ms-2">{{ session('kode_peminjaman') }}</span>
                    <br><small class="text-muted">Format: NAMA-TANGGAL-URUTAN</small>
                    <br><small class="text-muted">Contoh: JOH-20241201-0001, SAR-20241201-0002, MIK-20241201-0003, ANA-20241201-0004, DAV-20241201-0005, EMM-20241201-0006, JAM-20241201-0007</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Filter Form -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Format Kode:</strong> NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)
                    </div>
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Kode Peminjaman</label>
                            <input type="text" name="kode_peminjaman" class="form-control" placeholder="Contoh: JOH-20241201-0001" value="{{ request('kode_peminjaman') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control" placeholder="Cari nama kegiatan..." value="{{ request('nama_kegiatan') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="pengembalian_diajukan" {{ request('status') == 'pengembalian_diajukan' ? 'selected' : '' }}>Pengembalian Diajukan</option>
                                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                <option value="pengembalian ditolak" {{ request('status') == 'pengembalian ditolak' ? 'selected' : '' }}>Pengembalian Ditolak</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <a href="{{ route('list.peminjam') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- List Peminjam -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Peminjam</h5>
                </div>
                <div class="card-body">
                    @if($peminjamans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Peminjaman</th>
                                        <th>Nama</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tanggal Kegiatan</th>
                                        <th>Status</th>
                                        <th>No. HP</th>
                                        <th>Lihat Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamans as $peminjaman)
                                    <tr>
                                        <td><span class="badge bg-dark">{{ $peminjaman->kode_peminjaman }}</span></td>
                                        <td><strong>{{ $peminjaman->nama }}</strong></td>
                                        <td>{{ Str::limit($peminjaman->nama_kegiatan, 30) }}</td>
                                        <td>${formatTanggal(peminjaman.tanggal_mulai)} s/d ${formatTanggal(peminjaman.tanggal_selesai)}</td>
                                        <td>
                                            @if($peminjaman->status == 'dikembalikan')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                                </span>
                                            @elseif($peminjaman->status == 'disetujui')
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-check-lg me-1"></i>Disetujui
                                                </span>
                                            @elseif($peminjaman->status == 'pengembalian_diajukan')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock me-1"></i>Pengembalian Diajukan
                                                </span>
                                            @elseif($peminjaman->status == 'pengembalian ditolak')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Pengembalian Ditolak
                                                </span>
                                            @elseif($peminjaman->status == 'ditolak')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-hourglass-split me-1"></i>Menunggu
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $peminjaman->no_telp }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info text-white" onclick="showDetailModal({{ $peminjaman->id }})">
                                                <i class="bi bi-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            Tidak ada data peminjam yang ditemukan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Peminjam -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="bi bi-person-circle me-2"></i>Detail Peminjam
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function formatTanggal(dateString, includeTime = false) {
    if (!dateString) return '-';
    
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    
    let formatted = `${day}/${month}/${year}`;
    
    if (includeTime) {
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        formatted += ` ${hours}:${minutes}`;
    }
    
    return formatted;
}

function showDetailModal(id) {
    // Reset modal content
    document.getElementById('modalBody').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>
    `;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
    
    // Fetch data
    fetch(`/api/list-peminjam/detail/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const peminjaman = data.data;
                document.getElementById('modalBody').innerHTML = generateDetailContent(peminjaman);
            } else {
                document.getElementById('modalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Gagal memuat data peminjam.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalBody').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Terjadi kesalahan saat memuat data.
                </div>
            `;
        });
}

function generateDetailContent(peminjaman) {
    const statusBadgeClass = peminjaman.status == 'dikembalikan' ? 'bg-success' : 
                            (peminjaman.status == 'disetujui' ? 'bg-primary' : 
                            (peminjaman.status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark'));
    
    return `
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-person me-2"></i>Informasi Peminjam
                </h6>
                <table class="table table-sm">
                    <tr>
                        <td class="fw-bold" style="width: 40%">Kode Peminjaman:</td>
                        <td><span class="badge bg-dark">${peminjaman.kode_peminjaman}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama:</td>
                        <td><strong>${peminjaman.nama}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Unit:</td>
                        <td>${peminjaman.unit}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">No. HP:</td>
                        <td>${peminjaman.no_telp}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Status:</td>
                        <td><span class="badge ${statusBadgeClass}">${peminjaman.status.charAt(0).toUpperCase() + peminjaman.status.slice(1)}</span></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-calendar-event me-2"></i>Informasi Kegiatan
                </h6>
                <table class="table table-sm">
                    <tr>
                        <td class="fw-bold" style="width: 40%">Nama Kegiatan:</td>
                        <td>${peminjaman.nama_kegiatan}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Mulai:</td>
                        <td>${formatTanggal(peminjaman.tanggal_mulai)}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Selesai:</td>
                        <td>${formatTanggal(peminjaman.tanggal_selesai)}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Pengajuan:</td>
                        <td>${formatTanggal(peminjaman.created_at, true)}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Terakhir Update:</td>
                        <td>${formatTanggal(peminjaman.updated_at, true)}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-image me-2"></i>Foto Peminjam
                </h6>
                ${peminjaman.foto_peminjam ? 
                    `<img src="${peminjaman.foto_peminjam}" class="img-fluid rounded" style="max-height: 200px;" alt="Foto Peminjam" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="alert alert-warning" style="display: none;"><i class="bi bi-exclamation-triangle me-2"></i>Foto tidak dapat dimuat</div>` : 
                    '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ada foto peminjam</div>'
                }
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-file-earmark-text me-2"></i>Bukti Kegiatan
                </h6>
                ${peminjaman.bukti ? 
                    `<a href="${peminjaman.bukti}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-2"></i>Lihat Bukti
                    </a>` : 
                    '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ada bukti kegiatan</div>'
                }
            </div>
        </div>
        
        <hr class="my-4">
        
        <h6 class="fw-bold text-primary mb-3">
            <i class="bi bi-box-seam me-2"></i>Barang yang Dipinjam (${peminjaman.details.length} item)
        </h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Qty Dipinjam</th>
                        <th>Qty Dikembalikan</th>
                        <th>Status</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    ${peminjaman.details.map((detail, index) => {
                        const qtyDikembalikan = detail.jumlah_dikembalikan || 0;
                        const statusPengembalian = qtyDikembalikan > 0 ? 
                            (qtyDikembalikan === detail.qty ? 'Lengkap' : 'Sebagian') : 'Belum Dikembalikan';
                        const statusBadgeClass = qtyDikembalikan === detail.qty ? 'bg-success' : 
                                               (qtyDikembalikan > 0 ? 'bg-warning text-dark' : 'bg-secondary');
                        
                        return `
                            <tr>
                                <td>${index + 1}</td>
                                <td><span class="badge bg-secondary">${detail.barang.kode}</span></td>
                                <td><strong>${detail.barang.nama}</strong></td>
                                <td>${detail.barang.kategori}</td>
                                <td><span class="badge bg-info">${detail.qty}</span></td>
                                <td><span class="badge bg-primary">${qtyDikembalikan}</span></td>
                                <td><span class="badge ${statusBadgeClass}">${statusPengembalian}</span></td>
                                <td>${detail.barang.satuan}</td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        </div>
        
        ${peminjaman.status === 'pengembalian_diajukan' ? `
            <div class="alert alert-warning mt-3">
                <i class="bi bi-clock me-2"></i>
                <strong>Status Pengembalian:</strong> Pengembalian sedang diajukan dan menunggu persetujuan admin.
            </div>
        ` : ''}
        
        ${peminjaman.status === 'dikembalikan' ? `
            <div class="alert alert-success mt-3">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Status Pengembalian:</strong> Semua barang telah berhasil dikembalikan dan diverifikasi admin.
            </div>
        ` : ''}
        
        ${peminjaman.status === 'pengembalian ditolak' ? `
            <div class="alert alert-danger mt-3">
                <i class="bi bi-x-circle me-2"></i>
                <strong>Status Pengembalian:</strong> Pengembalian ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.
            </div>
        ` : ''}
    `;
}
</script>
@endsection 