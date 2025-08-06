@extends('layouts.app')

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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
            </div>
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-5 mb-3 mb-md-0">
                            @php
                                $fotoArray = $barang->foto ? json_decode($barang->foto, true) : [];
                            @endphp
                            @if(count($fotoArray) > 0)
                            <div class="mb-2 text-center">
                                <img class="main-foto-barang w-100" src="{{ asset('storage/' . $fotoArray[0]) }}" style="border-radius:1rem;max-height:260px;object-fit:cover;max-width:320px;" alt="Foto utama">
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                @foreach($fotoArray as $i => $foto)
                                <img src="{{ asset('storage/' . $foto) }}" class="img-thumbnail selector-foto" data-foto="{{ asset('storage/' . $foto) }}" style="width:50px;height:50px;object-fit:cover;cursor:pointer;{{ $i==0?'border:2px solid #0d6efd;':'' }}" alt="Thumb {{ $i+1 }}">
                                @endforeach
                            </div>
                            @else
                            <div class="bg-light text-center py-5" style="border-radius:1rem;">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Tidak ada foto</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-7 d-flex flex-column justify-content-between">
                            <div>
                                <h2 class="text-primary mb-3">{{ $barang->nama }}</h2>
                                <p class="mb-3">{{ $barang->deskripsi }}</p>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Stok Tersedia:</strong></p>
                                        <span class="badge bg-success fs-6">{{ $barang->stok_tersedia }}</span>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Stok Dipinjam:</strong></p>
                                        <span class="badge bg-warning text-dark fs-6">{{ $barang->stok_dipinjam }}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p class="mb-1"><strong>Status:</strong></p>
                                        <span class="badge {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }} fs-6">{{ ucfirst($barang->status) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
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
    
    // Interaksi thumbnail
    document.querySelectorAll('.selector-foto').forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            document.querySelectorAll('.selector-foto').forEach(t => t.style.border = '');
            this.style.border = '2px solid #0d6efd';
            var mainFoto = document.querySelector('.main-foto-barang');
            if(mainFoto) mainFoto.setAttribute('src', this.getAttribute('data-foto'));
        });
    });
});
</script>
@endsection 