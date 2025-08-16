@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/photo-gallery.css') }}">
@endsection

@section('content')
<style>
    .btn:disabled {
        opacity: 0.6 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }
    
    .btn-secondary:disabled {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #fff !important;
    }
    
    .btn-secondary:disabled:hover {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
    
    .form-control:disabled {
        background-color: #e9ecef !important;
        opacity: 0.6 !important;
    }

    /* Photo carousel styling */
    .photo-carousel {
        height: 300px;
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .photo-placeholder {
        height: 300px;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
        margin-bottom: 2rem;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            @if(session('kode_peminjaman'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-receipt me-2"></i>
                    <strong>Kode Peminjaman Anda:</strong> 
                    <span class="badge bg-dark ms-2">{{ session('kode_peminjaman') }}</span>
                    <br><small class="text-muted">Format: NAMA-TANGGAL-URUTAN</small>
                    <br><small class="text-muted">Contoh: JOH-20241201-0001, SAR-20241201-0002, MIK-20241201-0003</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
            </div>
            
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Format Kode:</strong> NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)
            </div>
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Photo Section -->
                            @if($barang->hasPhotos())
                                @if($barang->photo_count > 1)
                                    <div id="barangPhotoCarousel" class="carousel slide photo-carousel photo-gallery" data-bs-ride="carousel">
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
                                                <img src="{{ Storage::url($photo) }}" class="d-block w-100" alt="Foto {{ $index + 1 }}">
                                            </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#barangPhotoCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#barangPhotoCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                @else
                                    <img src="{{ Storage::url($barang->main_photo) }}" alt="{{ $barang->nama }}" class="photo-carousel" style="object-fit: cover;">
                                @endif
                            @else
                                <div class="photo-placeholder">
                                    <i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            <div class="text-center mb-4">
                                <h2 class="text-primary mb-3">{{ $barang->nama }}</h2>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-primary bg-opacity-10">
                                        <div class="card-body text-center py-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-boxes text-primary"></i>
                                            </div>
                                            <h6 class="mb-0 text-primary fw-semibold">Stok Tersedia</h6>
                                            <p class="mb-0 text-muted small">{{ $barang->stok_tersedia }} unit</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
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
                            
                            <div class="mb-4">
                                <h6 class="text-muted small mb-2">Status</h6>
                                <span class="badge rounded-pill fs-6
                                    {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-{{ $barang->status == 'tersedia' ? 'check-circle' : 'right' }} me-1"></i>
                                    {{ ucfirst($barang->status) }}
                                </span>
                            </div>
                            
                            <div class="mt-4">
                                <form action="{{ route('keranjang.tambah') }}" method="POST" class="d-flex flex-column align-items-start gap-2">
                                    @csrf
                                    <div class="input-group mb-2" style="max-width:200px;">
                                        <span class="input-group-text">Jumlah</span>
                                        <input type="number" name="jumlah" class="form-control" min="1" max="{{ $barang->stok_tersedia }}" value="1" required {{ $barang->status !== 'tersedia' ? 'disabled' : '' }}>
                                    </div>
                                    <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                                    <button type="submit" class="btn btn-lg {{ $barang->status === 'tersedia' ? 'btn-primary' : 'btn-secondary' }}" {{ $barang->status !== 'tersedia' ? 'disabled' : '' }} style="{{ $barang->status !== 'tersedia' ? 'opacity: 0.6; cursor: not-allowed;' : '' }}" title="{{ $barang->status !== 'tersedia' ? 'Barang tidak tersedia untuk dipinjam (Stok: ' . $barang->stok_tersedia . ')' : 'Klik untuk menambah ke keranjang' }}">
                                        <i class="bi bi-cart-plus me-2"></i>
                                        @if($barang->status === 'tersedia')
                                            Tambah ke Keranjang
                                        @else
                                            Tidak Tersedia
                                        @endif
                                    </button>
                                </form>
                                @if($barang->status !== 'tersedia')
                                    <small class="text-muted mt-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Barang tidak tersedia untuk dipinjam saat ini
                                    </small>
                                    <div class="alert alert-warning mt-2" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Informasi:</strong> Barang ini sedang tidak tersedia untuk dipinjam. 
                                        Stok tersedia: <strong>{{ $barang->stok_tersedia }}</strong> dari total <strong>{{ $barang->stok }}</strong>.
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

<!-- Toast Notifikasi -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
  <div id="toastKeranjang" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastKeranjangMsg">
        Barang berhasil ditambahkan ke keranjang!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi tooltip Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection 