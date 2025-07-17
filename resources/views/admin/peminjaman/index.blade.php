@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Kelola Peminjaman</h2>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Cari nama/no hp..." value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="urut" class="form-select">
            <option value="terlama" {{ request('urut')=='terlama'?'selected':'' }}>Urut Terlama</option>
            <option value="terbaru" {{ request('urut')=='terbaru'?'selected':'' }}>Urut Terbaru</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-biru w-100">Filter</button>
    </div>
</form>
<div class="table-responsive">
    <table class="table align-middle">
        <thead class="table-primary">
            <tr>
                <th>Nama</th>
                <th>No HP</th>
                <th>Unit/Jurusan</th>
                <th>Tgl Pinjam</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $p)
            <tr>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->no_telp }}</td>
                <td>{{ $p->unit }}</td>
                <td>{{ $p->tanggal_mulai }} s/d {{ $p->tanggal_selesai }}</td>
                <td>{{ Str::limit($p->keperluan, 30) }}</td>
                <td><span class="badge bg-warning text-dark">{{ ucfirst($p->status) }}</span></td>
                <td>
                    <a href="{{ route('admin.peminjaman.show', $p->id) }}" class="btn btn-info btn-sm text-white">Detail</a>
                    <form action="{{ route('admin.peminjaman.approve', $p->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="return confirm('Approve peminjaman?')">Approve</button>
                    </form>
                    <form action="{{ route('admin.peminjaman.reject', $p->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak peminjaman?')">Reject</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Tidak ada peminjaman menunggu approve.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $peminjamans->withQueryString()->links() }}
</div>
@endsection 