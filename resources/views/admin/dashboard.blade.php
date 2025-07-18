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
        <div class="alert alert-info mb-0">Belum ada peminjaman yang menunggu approve.</div>
    </div>
</div>
@endsection 