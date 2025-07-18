@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Arsip Peminjaman & Pengembalian</h2>
    <a href="{{ route('admin.arsip.export', request()->all()) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> Download PDF</a>
</div>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Cari nama user..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
            <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
            <option value="pengembalian_diajukan" {{ request('status')=='pengembalian_diajukan'?'selected':'' }}>Pengembalian Diajukan</option>
            <option value="dikembalikan" {{ request('status')=='dikembalikan'?'selected':'' }}>Dikembalikan</option>
            <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
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
    <span class="badge bg-primary">Barang Terlaris: {{ $terlaris ? $terlaris->nama . ' (' . ($terlaris->details_count ?? 0) . 'x)' : '-' }}</span>
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
                <th>Nama Kegiatan</th>
                <th>Tujuan</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
                <th>Barang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $p)
            <tr>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->no_telp }}</td>
                <td>{{ $p->unit }}</td>
                <td>{{ Str::limit($p->nama_kegiatan, 20) }}</td>
                <td>{{ Str::limit($p->tujuan, 20) }}</td>
                <td>{{ $p->tanggal_mulai }} s/d {{ $p->tanggal_selesai }}</td>
                <td>
                    <span class="badge
                        @if($p->status == 'dikembalikan') bg-success
                        @elseif($p->status == 'disetujui') bg-primary
                        @elseif($p->status == 'pengembalian_diajukan') bg-warning text-dark
                        @elseif($p->status == 'ditolak' || $p->status == 'pengembalian ditolak') bg-danger
                        @else bg-secondary
                        @endif
                    ">
                        @if($p->status == 'pengembalian_diajukan')
                            Pengembalian Diajukan
                        @elseif($p->status == 'pengembalian ditolak')
                            Pengembalian Ditolak
                        @else
                            {{ ucfirst($p->status) }}
                        @endif
                    </span>
                </td>
                <td>
                    @foreach($p->details as $detail)
                        <span class="badge bg-info text-dark mb-1">{{ $detail->barang->nama ?? '-' }} ({{ $detail->jumlah }})</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('admin.arsip.exportSingle', $p->id) }}" class="btn btn-sm btn-danger" title="Download PDF"><i class="bi bi-file-earmark-pdf"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center">Tidak ada data arsip.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $peminjamans->withQueryString()->links() }}
</div>
@endsection 