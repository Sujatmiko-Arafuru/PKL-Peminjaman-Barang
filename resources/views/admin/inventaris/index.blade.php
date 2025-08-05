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
    <a href="{{ route('admin.inventaris.create') }}" class="btn btn-primary border-2" style="border: 2px solid #0d6efd; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <i class="bi bi-plus-lg me-2"></i> Tambah Barang
    </a>
    <div>
        <span class="badge bg-primary">Total Barang: {{ $barangs->count() }}</span>
        <span class="badge bg-info text-dark ms-2">Total Stok: {{ $barangs->sum('stok') }}</span>
        <span class="badge bg-warning text-dark ms-2">Stok Dipinjam: {{ $barangs->sum('stok_dipinjam') }}</span>
        <span class="badge bg-success ms-2">Stok Tersedia: {{ $barangs->sum('stok_tersedia') }}</span>
    </div>
</div>
<div class="table-responsive">
    <table class="table align-middle">
        <thead class="table-primary">
            <tr>
                <th>Nama</th>
                <th>Foto</th>
                <th>Stok Tersedia</th>
                <th>Stok Dipinjam</th>
                <th>Status</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
            <tr>
                <td><strong>{{ $barang->nama }}</strong></td>
                <td>
                    @php
                        $fotoArray = $barang->foto ? json_decode($barang->foto, true) : [];
                        $fotoUtama = $fotoArray && count($fotoArray) > 0 ? $fotoArray[0] : null;
                    @endphp
                    @if($fotoUtama)
                        <img src="{{ asset('storage/' . $fotoUtama) }}" alt="{{ $barang->nama }}" style="max-width:60px;max-height:60px;border-radius:0.5rem;object-fit:cover;">
                    @else
                        <span class="text-muted"><i class="bi bi-image"></i></span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-success fs-6">{{ $barang->stok_tersedia }}</span>
                </td>
                <td>
                    <span class="badge bg-warning text-dark fs-6">{{ $barang->stok_dipinjam }}</span>
                </td>
                <td>
                    <span class="badge {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }} fs-6">
                        {{ ucfirst($barang->status) }}
                    </span>
                </td>
                <td>{{ Str::limit($barang->deskripsi, 40) }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.inventaris.show', $barang->id) }}" class="btn btn-info btn-sm text-white border-2" style="border: 2px solid #0dcaf0;">
                            <i class="bi bi-eye me-1"></i> Detail
                        </a>
                        <a href="{{ route('admin.inventaris.edit', $barang->id) }}" class="btn btn-outline-primary btn-sm border-2" style="border: 2px solid #0d6efd;">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <form action="{{ route('admin.inventaris.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus barang?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm border-2" style="border: 2px solid #dc3545;">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 