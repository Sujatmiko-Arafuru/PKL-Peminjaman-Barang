@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="/" class="btn btn-outline-primary">&larr; Kembali ke Dashboard</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-barang p-4">
                <div class="row">
                    <div class="col-md-5 mb-3 mb-md-0">
                        @if($barang->foto)
                        <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}" class="w-100" style="border-radius:1rem;max-height:260px;object-fit:cover;">
                        @else
                        <div class="bg-light text-center py-5" style="border-radius:1rem;">Tidak ada foto</div>
                        @endif
                    </div>
                    <div class="col-md-7 d-flex flex-column justify-content-between">
                        <div>
                            <h2 class="text-primary mb-2">{{ $barang->nama }}</h2>
                            <p class="mb-2">{{ $barang->deskripsi }}</p>
                            <p class="mb-1">Stok: <span class="fw-bold">{{ $barang->stok }}</span></p>
                            <p>Status: <span class="badge-status {{ $barang->status == 'tersedia' ? 'badge-tersedia' : 'badge-tidak' }}">{{ ucfirst($barang->status) }}</span></p>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-biru btn-keranjang-detail" data-nama="{{ $barang->nama }}">Tambah ke Keranjang</button>
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
    });
</script>
@endsection 