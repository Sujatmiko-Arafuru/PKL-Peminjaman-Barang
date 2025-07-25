@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Arsip Peminjaman & Pengembalian</h2>
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
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}">
                        <i class="bi bi-eye"></i> Detail
                    </button>

                    <!-- Modal Detail -->
                    <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel{{ $p->id }}">Detail Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-md-6 mb-3">
                                <div class="card">
                                  <div class="card-body">
                                    <h5 class="card-title text-primary">Data Peminjam</h5>
                                    <p class="mb-1">Nama: <b>{{ $p->nama }}</b></p>
                                    <p class="mb-1">No HP: <b>{{ $p->no_telp }}</b></p>
                                    <p class="mb-1">Unit/Jurusan: <b>{{ $p->unit }}</b></p>
                                    <p class="mb-1">Nama Kegiatan: <b>{{ $p->nama_kegiatan }}</b></p>
                                    <p class="mb-1">Tujuan: <b>{{ $p->tujuan }}</b></p>
                                    <p class="mb-1">Tanggal Pinjam: <b>{{ $p->tanggal_mulai }} s/d {{ $p->tanggal_selesai }}</b></p>
                                    <p class="mb-1">Kode Peminjaman: <b>{{ $p->kode_peminjaman }}</b></p>
                                    <p class="mb-1">Lampiran: 
                                        @if($p->bukti)
                                            <a href="{{ asset('storage/' . $p->bukti) }}" target="_blank" class="btn btn-sm btn-info text-white">Lihat Bukti</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </p>
                                    <p class="mb-1">Status: <span class="badge
                                        @if($p->status == 'dikembalikan') bg-success
                                        @elseif($p->status == 'disetujui') bg-primary
                                        @elseif($p->status == 'ditolak') bg-danger
                                        @else bg-warning text-dark
                                        @endif
                                    ">{{ ucfirst($p->status) }}</span></p>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                <div class="card">
                                  <div class="card-body">
                                    <h5 class="card-title text-primary">Barang yang Dipinjam</h5>
                                    <ul class="list-group">
                                      @foreach($p->details as $detail)
                                      <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $detail->barang->nama ?? '-' }}
                                        <span class="badge bg-primary">{{ $detail->jumlah }}</span>
                                      </li>
                                      @endforeach
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
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