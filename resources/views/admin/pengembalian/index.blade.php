@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-arrow-return-left me-2"></i>Kelola Pengembalian Barang
            </h2>
            <p class="text-muted mb-0">Input dan kelola pengembalian barang yang dipinjam</p>
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

    <!-- Form Input Pengembalian -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-search me-2"></i>Form Input Pengembalian
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pengembalian.input-kode') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Kode Peminjaman</label>
                        <input type="text" name="kode_peminjaman" class="form-control" 
                               placeholder="Contoh: ANG-20250814-0001" value="{{ old('kode_peminjaman') }}">
                    <small class="text-muted">Opsional - bisa dikosongkan</small>
                </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Nama Peminjam</label>
                        <input type="text" name="nama" class="form-control" 
                               placeholder="Nama lengkap peminjam" value="{{ old('nama') }}">
                    <small class="text-muted">Opsional - bisa dikosongkan</small>
                </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" 
                               placeholder="Nama kegiatan yang dilakukan" value="{{ old('nama_kegiatan') }}">
                    <small class="text-muted">Opsional - bisa dikosongkan</small>
                </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">No HP</label>
                        <input type="text" name="no_telp" class="form-control" 
                               placeholder="Nomor HP peminjam" value="{{ old('no_telp') }}">
                    <small class="text-muted">Opsional - bisa dikosongkan</small>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-search me-2"></i>Cari Data Peminjaman
                    </button>
                    <div class="text-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Admin bisa mengisi salah satu atau lebih form untuk memastikan data yang benar</strong>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Informasi Pengembalian -->
    <div class="row mb-4">
                <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-gear me-2"></i>Cara Kerja Sistem
                    </h6>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li>Admin mengisi form pencarian (bisa salah satu atau lebih)</li>
                        <li>Sistem mencari data peminjaman yang sesuai</li>
                        <li>Admin melihat detail dan input jumlah barang yang dikembalikan</li>
                        <li>Status otomatis berubah berdasarkan jumlah yang dikembalikan</li>
                        <li>Mahasiswa bisa lihat status di menu "List Peminjam"</li>
                    </ol>
                </div>
            </div>
                </div>
                <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-eye me-2"></i>Status yang Dapat Dilihat Mahasiswa
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-warning text-dark">Proses Pengembalian</span>
                        <small class="text-muted ms-2">- Sedang diproses admin</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-success">Dikembalikan</span>
                        <small class="text-muted ms-2">- Sudah selesai dan diverifikasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List Peminjaman yang Bisa Dikembalikan -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-list me-2"></i>Daftar Peminjaman yang Bisa Dikembalikan
            </h6>
        </div>
        <div class="card-body p-0">
            @if($peminjamans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kode</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Nama & NIM/NIP</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kegiatan</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Tanggal</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Progress</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Status</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamans as $peminjaman)
                            <tr class="border-bottom">
                                <td class="px-3 py-3">
                                    <span class="badge bg-dark">{{ $peminjaman->kode_peminjaman }}</span>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="fw-semibold text-dark">{{ $peminjaman->nama }}</div>
                                    <div class="text-muted small">{{ $peminjaman->nim_nip ?? 'NIM/NIP tidak tersedia' }}</div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="text-muted" title="{{ $peminjaman->nama_kegiatan }}">
                                        {{ Str::limit($peminjaman->nama_kegiatan, 40) }}
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="text-muted small">
                                        <div>{{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d/m/Y') }}</div>
                                        <div>s/d {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">{{ $peminjaman->total_dikembalikan }}/{{ $peminjaman->total_barang }}</small>
                                            <small class="text-muted">{{ number_format($peminjaman->percentage_returned, 0) }}%</small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $peminjaman->percentage_returned }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    @php($derivedStatus = $peminjaman->status_pengembalian)
                                    <span class="badge rounded-pill
                                        @if($derivedStatus == 'proses_pengembalian') bg-warning text-dark
                                        @elseif($derivedStatus == 'dipinjam') bg-primary
                                        @elseif($derivedStatus == 'disetujui') bg-success
                                        @elseif($derivedStatus == 'dikembalikan') bg-success
                                        @else bg-secondary
                                        @endif">
                                        @if($derivedStatus == 'proses_pengembalian')
                                            <i class="bi bi-clock me-1"></i>Proses Pengembalian
                                        @elseif($derivedStatus == 'dipinjam')
                                            <i class="bi bi-box-seam me-1"></i>Dipinjam
                                        @elseif($derivedStatus == 'disetujui')
                                            <i class="bi bi-check-circle me-1"></i>Disetujui
                                        @elseif($derivedStatus == 'dikembalikan')
                                            <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                        @else
                                            {{ ucfirst($derivedStatus) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    <a href="{{ route('admin.pengembalian.show', $peminjaman->id) }}" 
                                       class="btn btn-sm btn-outline-primary shadow-sm">
                                        <i class="bi bi-eye me-1"></i>Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Tidak ada peminjaman yang bisa dikembalikan saat ini</p>
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