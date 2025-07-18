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

<!-- Tabel Menunggu Approve -->
<div class="mb-5">
    <h4 class="mb-3">Menunggu Persetujuan</h4>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Unit/Jurusan</th>
                    <th>Nama Kegiatan</th>
                    <th>Tujuan</th>
                    <th>Tgl Pinjam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menunggu as $p)
                <tr>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->no_telp }}</td>
                    <td>{{ $p->unit }}</td>
                    <td>{{ Str::limit($p->nama_kegiatan, 20) }}</td>
                    <td>{{ Str::limit($p->tujuan, 20) }}</td>
                    <td>{{ $p->tanggal_mulai }} s/d {{ $p->tanggal_selesai }}</td>
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
                <tr><td colspan="8" class="text-center">Tidak ada peminjaman menunggu approve.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tabel Sedang Berlangsung -->
<div>
    <h4 class="mb-3">Sedang Berlangsung</h4>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-success">
                <tr>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Unit/Jurusan</th>
                    <th>Nama Kegiatan</th>
                    <th>Tujuan</th>
                    <th>Tgl Pinjam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sedang_berlangsung as $p)
                <tr>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->no_telp }}</td>
                    <td>{{ $p->unit }}</td>
                    <td>{{ Str::limit($p->nama_kegiatan, 20) }}</td>
                    <td>{{ Str::limit($p->tujuan, 20) }}</td>
                    <td>{{ $p->tanggal_mulai }} s/d {{ $p->tanggal_selesai }}</td>
                    <td><span class="badge bg-primary">{{ ucfirst($p->status) }}</span></td>
                    <td>
                        <a href="{{ route('admin.peminjaman.show', $p->id) }}" class="btn btn-info btn-sm text-white">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">Tidak ada peminjaman sedang berlangsung.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 