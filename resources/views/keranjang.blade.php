@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="dashboard-title">Keranjang Peminjaman</h1>
    <div class="mb-3">
        <a href="/" class="btn btn-outline-primary">&larr; Kembali ke Dashboard</a>
    </div>
    @if(count($cart) > 0)
    <div class="table-responsive mb-4">
        <table class="table align-middle">
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
                        <img src="{{ asset('storage/' . $item['foto']) }}" alt="{{ $item['nama'] }}" style="max-width:60px;max-height:60px;border-radius:0.5rem;">
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['stok'] }}</td>
                    <td><span class="badge-status {{ $item['status'] == 'tersedia' ? 'badge-tersedia' : 'badge-tidak' }}">{{ ucfirst($item['status']) }}</span></td>
                    <td>{{ $item['qty'] }}</td>
                    <td>
                        <form action="{{ route('keranjang.hapus', $item['id']) }}" method="POST" onsubmit="return confirm('Hapus barang dari keranjang?')">
                            @csrf
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end">
        <a href="#" class="btn btn-biru">Lanjutkan Peminjaman</a>
    </div>
    @else
    <div class="alert alert-info">Keranjang masih kosong.</div>
    @endif
</div>
@endsection 