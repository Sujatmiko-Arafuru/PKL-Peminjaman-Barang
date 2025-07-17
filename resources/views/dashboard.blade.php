@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="dashboard-title mb-0">Dashboard Peminjaman Barang</h1>
        <a href="{{ route('keranjang.index') }}" class="btn btn-biru">Keranjang <span id="cart-count" class="badge-status badge-tersedia ms-1">{{ session('cart') ? count(session('cart')) : 0 }}</span></a>
    </div>
    <form method="GET" action="/" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama barang..." value="{{ request('search') }}">
            <button class="btn btn-biru" type="submit">Cari</button>
        </div>
    </form>
    <div class="grid-barang">
        @forelse($barangs as $barang)
        <div class="card-barang p-3 d-flex flex-column justify-content-between">
            @if($barang->foto)
            <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}" style="max-height:160px;object-fit:cover;border-radius:0.7rem;" class="mb-2 w-100">
            @endif
            <div>
                <h5 class="text-primary mb-1">{{ $barang->nama }}</h5>
                <p class="mb-1">{{ Str::limit($barang->deskripsi, 60) }}</p>
                <p class="mb-1">Stok: <span class="fw-bold">{{ $barang->stok }}</span></p>
                <p>Status: <span class="badge-status {{ $barang->status == 'tersedia' ? 'badge-tersedia' : 'badge-tidak' }}">{{ ucfirst($barang->status) }}</span></p>
            </div>
            <div class="mt-2 d-flex gap-2">
                <a href="{{ route('barang.detail', $barang->id) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                <button type="button" class="btn btn-biru btn-sm btn-keranjang" data-id="{{ $barang->id }}" data-nama="{{ $barang->nama }}">Tambah ke Keranjang</button>
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