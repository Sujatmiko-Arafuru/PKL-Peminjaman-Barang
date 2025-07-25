@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Dashboard Admin SarPras</h2>
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="fs-2 text-primary"><i class="bi bi-hourglass-split"></i></div>
                <div class="fw-bold fs-4">{{ $menungguApprove }}</div>
                <div class="text-muted">Menunggu Approve Peminjaman</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="fs-2 text-primary"><i class="bi bi-people"></i></div>
                <div class="fw-bold fs-4">{{ $totalPengguna }}</div>
                <div class="text-muted">Total Pengguna</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="fs-2 text-primary"><i class="bi bi-arrow-repeat"></i></div>
                <div class="fw-bold fs-4">{{ $menungguPengembalian }}</div>
                <div class="text-muted">Menunggu Approve Pengembalian</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="fs-2 text-primary"><i class="bi bi-box-seam"></i></div>
                <div class="fw-bold fs-4">{{ $totalBarang }}</div>
                <div class="text-muted">Total Barang</div>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold">Quick Approve Peminjaman</div>
    <div class="card-body">
        @if($quickApprove->count() > 0)
        <div class="table-responsive mb-0">
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
                    @foreach($quickApprove as $p)
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
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info mb-0">Belum ada peminjaman yang menunggu approve.</div>
        @endif
    </div>
</div>
@endsection 