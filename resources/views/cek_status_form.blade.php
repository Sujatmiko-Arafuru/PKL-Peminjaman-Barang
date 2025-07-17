@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="dashboard-title mb-3"><i class="bi bi-search me-2"></i>Cek Status Peminjaman</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('cekStatus.submit') }}" method="POST" class="mb-4 bg-white p-4 rounded shadow-sm" style="max-width:400px;">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Kode Unik</label>
            <input type="text" name="kode_unik" class="form-control" required>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cek Status</button>
        </div>
    </form>
</div>
@endsection 