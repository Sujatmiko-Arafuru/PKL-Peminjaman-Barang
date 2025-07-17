@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="dashboard-title mb-3"><i class="bi bi-cart3 me-2"></i>Keranjang Peminjaman</h1>
    <div class="mb-3">
        <a href="/" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
    @if(count($cart) > 0)
    <div class="table-responsive mb-4">
        <table class="table align-middle table-bordered shadow-sm bg-white">
            <thead class="table-primary">
                <tr>
                    <th>Foto</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr>
                    <td style="width:80px">
                        @if($item['foto'])
                        <img src="{{ asset('storage/' . $item['foto']) }}" alt="{{ $item['nama'] }}" class="rounded" style="max-width:60px;max-height:60px;">
                        @else
                        <span class="text-muted"><i class="bi bi-image"></i></span>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $item['nama'] }}</td>
                    <td>{{ $item['stok'] }}</td>
                    <td><span class="badge {{ $item['status'] == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($item['status']) }}</span></td>
                    <td>{{ $item['qty'] }}</td>
                    <td>
                        <form action="{{ route('keranjang.hapus', $item['id']) }}" method="POST" onsubmit="return confirm('Hapus barang dari keranjang?')">
                            @csrf
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end">
        <a href="{{ route('peminjaman.form') }}" class="btn btn-success"><i class="bi bi-arrow-right-circle"></i> Lanjutkan Peminjaman</a>
    </div>
    @else
    <div class="alert alert-info"><i class="bi bi-info-circle"></i> Keranjang masih kosong.</div>
    @endif
</div>
@endsection 