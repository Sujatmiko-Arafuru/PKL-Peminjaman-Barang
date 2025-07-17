@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Arsip Peminjaman & Pengembalian</h2>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Cari nama user..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
            <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
            <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
            <option value="dikembalikan" {{ request('status')=='dikembalikan'?'selected':'' }}>Dikembalikan</option>
            <option value="pengembalian ditolak" {{ request('status')=='pengembalian ditolak'?'selected':'' }}>Pengembalian Ditolak</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
    </div>
    <div class="col-md-2">
        <select name="urut" class="form-select">
            <option value="terbaru" {{ request('urut')=='terbaru'?'selected':'' }}>Urut Terbaru</option>
            <option value="terlama" {{ request('urut')=='terlama'?'selected':'' }}>Urut Terlama</option>
        </select>
    </div>
    <div class="col-md-1">
        <button class="btn btn-biru w-100">Filter</button>
    </div>
</form>
<div class="mb-3">
    <span class="badge bg-primary">Barang Terlaris: {{ $terlaris ? $terlaris->nama . ' (' . ($terlaris->total_dipinjam ?? 0) . 'x)' : '-' }}</span>
    <span class="badge bg-secondary ms-2">Barang Tidak Pernah Dipinjam: 
        @if($tidakPernah && count($tidakPernah) > 0)
            {{ implode(', ', array_map(fn($b) => $b->nama, $tidakPernah)) }}
        @else
            -
        @endif
    </span>
</div>
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
                <th>Barang</th>
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
                <td><span class="badge {{ $p->status == 'dikembalikan' ? 'bg-success' : ($p->status == 'disetujui' ? 'bg-primary' : ($p->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark')) }}">{{ ucfirst($p->status) }}</span></td>
                <td>
                    @foreach($p->details as $detail)
                        <span class="badge bg-info text-dark mb-1">{{ $detail->barang->nama ?? '-' }} ({{ $detail->jumlah }})</span>
                    @endforeach
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Tidak ada data arsip.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $peminjamans->withQueryString()->links() }}
</div>
@endsection 