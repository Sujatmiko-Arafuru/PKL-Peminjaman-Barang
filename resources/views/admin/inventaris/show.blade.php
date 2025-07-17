@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Detail Barang Inventaris</h2>
<a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-primary mb-3">&larr; Kembali ke Inventaris</a>
<div class="row">
    <div class="col-md-5 mb-3">
        @php $fotos = $barang->foto ? json_decode($barang->foto, true) : []; @endphp
        @if(count($fotos) > 0)
            <div id="fotoCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($fotos as $i => $foto)
                    <div class="carousel-item{{ $i==0 ? ' active' : '' }}">
                        <img src="{{ asset('storage/' . $foto) }}" class="d-block w-100" style="max-height:320px;object-fit:cover;border-radius:1rem;">
                    </div>
                    @endforeach
                </div>
                @if(count($fotos) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#fotoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#fotoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                @endif
            </div>
        @else
            <div class="bg-light text-center py-5" style="border-radius:1rem;">Tidak ada foto</div>
        @endif
    </div>
    <div class="col-md-7">
        <h4 class="text-primary mb-2">{{ $barang->nama }}</h4>
        <p class="mb-1">Stok: <span class="fw-bold">{{ $barang->stok }}</span></p>
        <p class="mb-1">Stok Dipinjam: <span class="fw-bold">{{ $barang->getOriginal('stok') - $barang->stok }}</span></p>
        <p class="mb-1">Status: <span class="badge {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($barang->status) }}</span></p>
        <p class="mb-2">Deskripsi: <br>{{ $barang->deskripsi }}</p>
        <a href="{{ route('admin.inventaris.edit', $barang->id) }}" class="btn btn-biru">Edit Barang</a>
    </div>
</div>
@endsection 