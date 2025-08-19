@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-search me-2"></i>Hasil Pencarian Pengembalian
            </h2>
            <p class="text-muted mb-0">Pilih peminjaman yang akan dikelola pengembaliannya</p>
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

    <!-- Hasil Pencarian -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-list me-2"></i>Data Peminjaman yang Ditemukan ({{ $peminjamans->count() }} item)
            </h6>
        </div>
        <div class="card-body p-0">
            @if($peminjamans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kode</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Nama & Unit</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Kegiatan</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Tanggal</th>
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
                                    <div class="text-muted small">{{ $peminjaman->unit }}</div>
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
                                    <span class="badge rounded-pill
                                        @if($peminjaman->status == 'disetujui') bg-success
                                        @elseif($peminjaman->status == 'dipinjam') bg-primary
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($peminjaman->status) }}
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
                    <p class="text-muted mt-3">Tidak ada data peminjaman yang ditemukan</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-info-circle me-2"></i>Informasi
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-lightbulb me-2"></i>Tips Pencarian
                    </h6>
                    <ul class="mb-0">
                        <li>Gunakan kode peminjaman untuk hasil yang paling akurat</li>
                        <li>Nama peminjam bisa digunakan jika kode tidak diketahui</li>
                        <li>Nama kegiatan membantu jika ada kegiatan yang sama</li>
                        <li>No HP bisa digunakan untuk verifikasi tambahan</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-gear me-2"></i>Langkah Selanjutnya
                    </h6>
                    <ol class="mb-0">
                        <li>Klik "Lihat Detail" pada peminjaman yang sesuai</li>
                        <li>Periksa detail barang yang dipinjam</li>
                        <li>Update jumlah barang yang dikembalikan</li>
                        <li>Approve atau reject pengembalian</li>
                    </ol>
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

.btn-sm {
    font-size: 0.875rem;
}
</style>
@endsection
