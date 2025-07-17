@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="dashboard-title">Form Pengajuan Peminjaman</h1>
    <div class="mb-3">
        <a href="{{ route('keranjang.index') }}" class="btn btn-outline-primary">&larr; Kembali ke Keranjang</a>
    </div>
    <form action="{{ route('peminjaman.ajukan') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nama</label>
                <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Unit / Jurusan</label>
                <input type="text" name="unit" class="form-control" required value="{{ old('unit') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">No. Telepon</label>
                <input type="text" name="no_telp" class="form-control" required value="{{ old('no_telp') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" class="form-control" required value="{{ old('tanggal_mulai') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" class="form-control" required value="{{ old('tanggal_selesai') }}">
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Keperluan</label>
                <textarea name="keperluan" class="form-control" rows="2" required>{{ old('keperluan') }}</textarea>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Lampiran Bukti (PDF)</label>
                <input type="file" name="bukti" class="form-control" accept="application/pdf" required>
            </div>
        </div>
        <h5 class="mb-2">Barang yang Dipinjam:</h5>
        <div class="table-responsive mb-3">
            <table class="table align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $item)
                    <tr>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-biru">Ajukan Peminjaman</button>
        </div>
    </form>
</div>
@endsection 