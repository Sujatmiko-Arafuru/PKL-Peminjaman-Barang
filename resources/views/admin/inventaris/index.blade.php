@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Inventaris Barang</h2>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('admin.inventaris.create') }}" class="btn btn-biru">+ Tambah Barang</a>
    <div>
        <span class="badge bg-primary">Total Barang: {{ $barangs->count() }}</span>
        <span class="badge bg-info text-dark ms-2">Total Stok: {{ $barangs->sum('stok') }}</span>
        <span class="badge bg-warning text-dark ms-2">Stok Dipinjam: {{ $barangs->sum(function($b){return $b->getOriginal('stok') - $b->stok;}) }}</span>
    </div>
</div>
<div class="table-responsive">
    <table class="table align-middle">
        <thead class="table-primary">
            <tr>
                <th>Nama</th>
                <th>Foto</th>
                <th>Stok</th>
                <th>Stok Dipinjam</th>
                <th>Status</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
            <tr>
                <td>{{ $barang->nama }}</td>
                <td>
                    @if($barang->foto && count(json_decode($barang->foto, true)) > 0)
                        <img src="{{ asset('storage/' . json_decode($barang->foto, true)[0]) }}" alt="{{ $barang->nama }}" style="max-width:60px;max-height:60px;border-radius:0.5rem;">
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ $barang->stok }}</td>
                <td>{{ $barang->getOriginal('stok') - $barang->stok }}</td>
                <td><span class="badge {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($barang->status) }}</span></td>
                <td>{{ Str::limit($barang->deskripsi, 40) }}</td>
                <td>
                    <a href="{{ route('admin.inventaris.show', $barang->id) }}" class="btn btn-info btn-sm text-white">Detail</a>
                    <a href="{{ route('admin.inventaris.edit', $barang->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <form action="{{ route('admin.inventaris.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus barang?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 