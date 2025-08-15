@extends('admin.layouts.app')

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
        <!-- Image Section -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-image me-2"></i>Foto Barang
                    </h6>
                </div>
                <div class="card-body p-0">
                    @php $photos = $barang->getAllPhotos(); @endphp
                    @if(count($photos) > 0)
                        <div id="fotoCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($photos as $i => $photo)
                                <div class="carousel-item{{ $i==0 ? ' active' : '' }}">
                                    <img src="{{ Storage::url('public/barang-photos/' . $photo) }}" 
                                         class="d-block w-100" 
                                         style="max-height:400px;object-fit:cover;border-radius:0.75rem;">
                                </div>
                                @endforeach
                            </div>
                            @if(count($photos) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#fotoCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#fotoCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                            @endif
                        </div>
                        @if(count($photos) > 1)
                        <div class="text-center py-2">
                            <small class="text-muted">{{ count($photos) }} foto tersedia</small>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" 
                                 style="width:200px;height:200px;">
                                <div class="text-muted">
                                    <i class="bi bi-image fs-1"></i>
                                    <p class="mb-0 mt-2">Tidak ada foto</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Information Section -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Informasi Barang
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Item Name -->
                    <div class="mb-4">
                        <h4 class="text-primary fw-bold mb-1">{{ $barang->nama }}</h4>
                        <small class="text-muted">ID: {{ $barang->id }}</small>
                    </div>

                    <!-- Stock Information -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 bg-primary bg-opacity-10">
                                <div class="card-body text-center py-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-boxes text-primary"></i>
                                    </div>
                                    <h6 class="mb-0 text-primary fw-semibold">Total Stok</h6>
                                    <p class="mb-0 text-muted small">{{ $barang->stok }} unit</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 bg-success bg-opacity-10">
                                <div class="card-body text-center py-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-check-circle text-success"></i>
                                    </div>
                                    <h6 class="mb-0 text-success fw-semibold">Stok Tersedia</h6>
                                    <p class="mb-0 text-muted small">{{ $barang->stok_tersedia }} unit</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 bg-warning bg-opacity-10">
                                <div class="card-body text-center py-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-arrow-repeat text-warning"></i>
                                    </div>
                                    <h6 class="mb-0 text-warning fw-semibold">Stok Dipinjam</h6>
                                    <p class="mb-0 text-muted small">{{ $barang->stok_dipinjam }} unit</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <h6 class="text-muted small mb-2">Status Barang</h6>
                        <span class="badge rounded-pill fs-6
                            {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">
                            <i class="bi bi-{{ $barang->status == 'tersedia' ? 'check-circle' : 'x-circle' }} me-1"></i>
                            {{ ucfirst($barang->status) }}
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="text-muted small mb-2">Deskripsi</h6>
                        <div class="bg-light rounded p-3">
                            @if($barang->deskripsi)
                                <p class="mb-0">{{ $barang->deskripsi }}</p>
                            @else
                                <p class="mb-0 text-muted">Tidak ada deskripsi</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.inventaris.edit', $barang->id) }}" 
                           class="btn btn-primary shadow-sm">
                            <i class="bi bi-pencil me-2"></i>Edit Barang
                        </a>
                        <form action="{{ route('admin.inventaris.destroy', $barang->id) }}" 
                              method="POST" class="d-inline" 
                              onsubmit="return confirm('Yakin hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger shadow-sm">
                                <i class="bi bi-trash me-2"></i>Hapus Barang
                            </button>
                        </form>
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

.carousel-control-prev,
.carousel-control-next {
    background-color: rgba(0,0,0,0.3);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    top: 50%;
    transform: translateY(-50%);
}

.carousel-control-prev {
    left: 10px;
}

.carousel-control-next {
    right: 10px;
}

.badge {
    font-size: 0.875rem;
}

.btn {
    font-size: 0.875rem;
}
</style>
@endsection 