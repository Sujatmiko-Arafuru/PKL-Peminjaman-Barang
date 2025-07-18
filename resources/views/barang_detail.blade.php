@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3 col-lg-2">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3"><i class="bi bi-list"></i> Menu</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam me-2"></i>List Barang
                        </a>
                        <a href="{{ route('keranjang.index') }}" class="btn btn-outline-primary position-relative">
                            <i class="bi bi-cart3 me-2"></i>Keranjang
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </a>
                        <a href="{{ route('list.peminjam') }}" class="btn btn-outline-info">
                            <i class="bi bi-people me-2"></i>List Peminjam
                        </a>
                        <a href="{{ route('cekStatus.form') }}" class="btn btn-outline-success">
                            <i class="bi bi-arrow-repeat me-2"></i>Pengembalian
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
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
                                <img id="mainFoto" src="{{ asset('storage/' . $fotoArray[0]) }}" class="w-100" style="border-radius:1rem;max-height:260px;object-fit:cover;max-width:320px;" alt="Foto utama">
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
                                        <p class="mb-1"><strong>Stok:</strong></p>
                                        <span class="badge bg-primary fs-6">{{ $barang->stok }}</span>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Status:</strong></p>
                                        <span class="badge {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }} fs-6">{{ ucfirst($barang->status) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary btn-lg btn-keranjang-detail" data-nama="{{ $barang->nama }}">
                                    <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.querySelector('.btn-keranjang-detail');
        if(btn) {
            btn.addEventListener('click', function() {
                const nama = this.getAttribute('data-nama');
                alert('Barang "' + nama + '" ditambahkan ke keranjang! (simulasi UI)');
            });
        }
        // Interaksi thumbnail: klik thumbnail ganti gambar utama
        var mainFoto = document.getElementById('mainFoto');
        var thumbs = document.querySelectorAll('.selector-foto');
        thumbs.forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                thumbs.forEach(t => t.style.border = '');
                this.style.border = '2px solid #0d6efd';
                if(mainFoto) mainFoto.src = this.getAttribute('data-foto');
            });
        });
    });
</script>
@endsection 