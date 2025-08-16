@extends('admin.layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/photo-gallery.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-box-seam me-2"></i>Detail Barang Inventaris
            </h2>
            <p class="text-muted mb-0">Informasi lengkap barang dan stok inventaris</p>
        </div>
        <div>
            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Inventaris
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Photo Section -->
        @if($barang->hasPhotos())
        <div class="col-lg-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-images me-2"></i>Foto Barang ({{ $barang->photo_count }} foto)
                    </h6>
                </div>
                <div class="card-body">
                    <div id="barangPhotoCarousel" class="carousel slide photo-gallery" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($barang->photos as $index => $photo)
                            <button type="button" data-bs-target="#barangPhotoCarousel" data-bs-slide-to="{{ $index }}" 
                                    class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                    aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($barang->photos as $index => $photo)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ Storage::url($photo) }}" class="d-block w-100" alt="Foto {{ $index + 1 }}" 
                                     style="height: 400px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                        @if($barang->photo_count > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#barangPhotoCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#barangPhotoCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Information Section -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Informasi Barang
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Kode Barang</label>
                            <p class="mb-0 fs-5">{{ $barang->kode ?? 'Tidak ada kode' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Nama Barang</label>
                            <p class="mb-0 fs-5">{{ $barang->nama }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Kategori</label>
                            <p class="mb-0">{{ $barang->kategori ?? 'Tidak ada kategori' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Satuan</label>
                            <p class="mb-0">{{ $barang->satuan ?? 'Tidak ada satuan' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Lokasi</label>
                            <p class="mb-0">{{ $barang->lokasi ?? 'Tidak ada lokasi' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Kondisi</label>
                            <p class="mb-0">{{ $barang->kondisi ?? 'Tidak ada kondisi' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold text-muted">Deskripsi</label>
                            <p class="mb-0">{{ $barang->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-boxes me-2"></i>Informasi Stok
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Total Stok</label>
                            <p class="mb-0 fs-4 fw-bold text-primary">{{ $barang->stok }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <span class="badge bg-{{ $barang->status == 'tersedia' ? 'success' : 'danger' }} fs-6">
                                {{ ucfirst($barang->status) }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Stok Tersedia</label>
                            <p class="mb-0 fs-5 fw-bold text-success">{{ $barang->stok_tersedia }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Stok Dipinjam</label>
                            <p class="mb-0 fs-5 fw-bold text-warning">{{ $barang->stok_dipinjam }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-gear me-2"></i>Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.inventaris.edit', $barang->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-2"></i>Edit Barang
                        </a>
                        <form action="{{ route('admin.inventaris.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Hapus Barang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 