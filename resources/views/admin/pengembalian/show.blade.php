@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-arrow-return-left me-2"></i>Detail Pengembalian Barang
            </h2>
            <p class="text-muted mb-0">Kelola pengembalian barang untuk peminjaman ini</p>
        </div>
        <div>
            <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Pengembalian
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

    <!-- Informasi Peminjaman -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-person me-2"></i>Informasi Peminjam
                    </h6>
                </div>
            <div class="card-body">
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
                            <td class="fw-bold">NIM/NIP:</td>
                            <td><strong>{{ $peminjaman->nim_nip ?? '-' }}</strong></td>
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
                                                                    @if($peminjaman->status == 'proses_pengembalian')
                                <span class="badge bg-warning text-dark rounded-pill">
                                            <i class="bi bi-clock me-1"></i>Proses Pengembalian
                                        </span>
                                    @elseif($peminjaman->status == 'dipinjam')
                                        <span class="badge bg-primary rounded-pill">
                                            <i class="bi bi-box-seam me-1"></i>Dipinjam
                                </span>
                            @elseif($peminjaman->status == 'disetujui')
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i>Disetujui
                                </span>
                                    @elseif($peminjaman->status == 'dikembalikan')
                                        <span class="badge bg-success rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">
                                            {{ ucfirst($peminjaman->status) }}
                                        </span>
                            @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-calendar-event me-2"></i>Informasi Kegiatan
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-bold" style="width: 40%">Nama Kegiatan:</td>
                            <td>{{ $peminjaman->nama_kegiatan }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Mulai:</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Selesai:</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Pengajuan:</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                                    </div>
                                </div>
                                </div>
                            </div>

    <!-- Progress Bar Pengembalian -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-graph-up me-2"></i>Progress Pengembalian
            </h6>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $peminjaman->percentage_returned }}%"
                             aria-valuenow="{{ $peminjaman->percentage_returned }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            {{ $peminjaman->percentage_returned }}%
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $peminjaman->total_dikembalikan }} dari {{ $peminjaman->total_barang }} barang sudah dikembalikan
                    </small>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex flex-column">
                        <span class="badge bg-success fs-6 mb-1">
                            {{ $peminjaman->total_dikembalikan }} Dikembalikan
                        </span>
                        <span class="badge bg-warning text-dark fs-6">
                            {{ $peminjaman->total_belum_dikembalikan }} Belum Dikembalikan
                        </span>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pengembalian Barang -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-box-seam me-2"></i>Form Pengembalian Barang
            </h6>
        </div>
        <div class="card-body">
            @if($peminjaman->details->count() > 0)
                <form action="{{ route('admin.pengembalian.bulk-update', $peminjaman->id) }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-3 py-3 text-muted small fw-semibold">Barang</th>
                                    <th class="border-0 px-3 py-3 text-muted small fw-semibold">Stok Tersedia</th>
                                    <th class="border-0 px-3 py-3 text-muted small fw-semibold">Jumlah Dipinjam</th>
                                    <th class="border-0 px-3 py-3 text-muted small fw-semibold">Sudah Dikembalikan</th>
                                    <th class="border-0 px-3 py-3 text-muted small fw-semibold">Jumlah Dikembalikan</th>
                                    <th class="border-0 px-3 py-3 text-muted small fw-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman->details as $detail)
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <div class="fw-semibold text-dark">{{ $detail->barang->nama }}</div>
                                        <small class="text-muted">{{ $detail->barang->deskripsi }}</small>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="badge bg-info fs-6">{{ $detail->barang->stok }}</span>
                                        <small class="text-muted d-block">Tersedia</small>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="badge bg-primary fs-6">{{ $detail->jumlah }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="badge bg-success fs-6">{{ $detail->jumlah_dikembalikan }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        @php($sisa = max(0, $detail->jumlah - $detail->jumlah_dikembalikan))
                                        <input type="number" 
                                               name="details[{{ $detail->id }}][id]" 
                                               value="{{ $detail->id }}" 
                                               hidden>
                                        <input type="number" 
                                               name="details[{{ $detail->id }}][jumlah_dikembalikan]" 
                                               class="form-control" 
                                               min="0" 
                                               max="{{ $sisa }}" 
                                               value="0"
                                               style="width: 80px;">
                                        <small class="text-muted d-block">
                                            Max tambahan: {{ $sisa }}
                                            @if($detail->jumlah_dikembalikan > 0)
                                                <br><span class="text-info">Sudah dikembalikan: {{ $detail->jumlah_dikembalikan }}</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="px-3 py-3">
                                        @if($detail->isAllReturned())
                                            <span class="badge bg-success rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i>Lengkap
                                            </span>
                                        @elseif($detail->isPartiallyReturned())
                                            <span class="badge bg-warning text-dark rounded-pill">
                                                <i class="bi bi-clock me-1"></i>Sebagian
                                            </span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">
                                                <i class="bi bi-x-circle me-1"></i>Belum
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Update jumlah barang yang dikembalikan sesuai dengan kondisi fisik barang</strong>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-lg me-2"></i>Update Pengembalian
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Tidak ada detail barang untuk peminjaman ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Informasi Status Pengembalian -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-info-circle me-2"></i>Informasi Status Pengembalian
            </h6>
        </div>
        <div class="card-body">
    <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-lightbulb me-2"></i>Logika Status
                    </h6>
                    <ul class="mb-0">
                        <li><strong>Disetujui</strong> → <strong>Dipinjam</strong> (setelah acara)</li>
                        <li><strong>Dipinjam</strong> → <strong>Proses Pengembalian</strong> (sebagian barang dikembalikan)</li>
                        <li><strong>Proses Pengembalian</strong> → <strong>Dikembalikan</strong> (semua barang dikembalikan)</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-eye me-2"></i>Status yang Dilihat Mahasiswa
                    </h6>
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

.progress {
    border-radius: 0.5rem;
}

.progress-bar {
    border-radius: 0.5rem;
}

.form-control {
    border-radius: 0.5rem;
}

.btn {
    border-radius: 0.5rem;
}
</style>
@endsection 