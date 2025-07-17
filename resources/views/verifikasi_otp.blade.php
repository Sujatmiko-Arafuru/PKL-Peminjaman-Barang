@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="dashboard-title mb-3"><i class="bi bi-shield-lock me-2"></i>Verifikasi OTP Email</h1>
    @if(session('info'))
        <div class="alert alert-info"><i class="bi bi-info-circle"></i> {{ session('info') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('peminjaman.verifikasiOtp.submit') }}" method="POST" class="mb-4 bg-white p-4 rounded shadow-sm" style="max-width:400px;">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Kode OTP (6 digit)</label>
            <input type="text" name="kode_otp" class="form-control text-center fs-4" maxlength="6" required autofocus autocomplete="one-time-code">
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success"><i class="bi bi-shield-check"></i> Verifikasi</button>
        </div>
    </form>
</div>
@endsection 