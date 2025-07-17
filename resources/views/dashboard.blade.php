@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="dashboard-title mb-0">Dashboard Peminjaman Barang</h1>
        <a href="{{ route('keranjang.index') }}" class="btn btn-primary position-relative">
            <i class="bi bi-cart3 me-1"></i> Keranjang
            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ session('cart') ? count(session('cart')) : 0 }}
            </span>
        </a>
    </div>
    <form method="GET" action="/" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama barang..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
        </div>
    </form>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($barangs as $barang)
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                @if($barang->foto)
                <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}" class="card-img-top" style="max-height:180px;object-fit:cover;">
                @else
                <div class="d-flex align-items-center justify-content-center bg-light" style="height:180px;border-radius:.5rem;">
                    <i class="bi bi-image text-secondary" style="font-size:2.5rem;"></i>
                </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary mb-1">{{ $barang->nama }}</h5>
                    <p class="card-text mb-1">{{ Str::limit($barang->deskripsi, 60) }}</p>
                    <p class="mb-1">Stok: <span class="fw-bold">{{ $barang->stok }}</span></p>
                    <p>Status: <span class="badge {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($barang->status) }}</span></p>
                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('barang.detail', $barang->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-info-circle"></i> Detail</a>
                        <button type="button" class="btn btn-success btn-sm btn-keranjang" data-id="{{ $barang->id }}" data-nama="{{ $barang->nama }}"><i class="bi bi-cart-plus"></i> Tambah</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">Barang tidak ditemukan.</div>
        </div>
        @endforelse
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-keranjang').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                fetch("{{ route('keranjang.tambah') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ barang_id: id })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('cart-count').innerText = data.cart_count;
                        alert('Barang "' + nama + '" ditambahkan ke keranjang!');
                    }
                });
            });
        });
    });
</script>
@endsection 