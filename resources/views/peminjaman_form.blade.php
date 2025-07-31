@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3 col-lg-2">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3"><i class="bi bi-list"></i> Menu</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam me-2"></i>List Barang
                        </a>
                        <a href="{{ route('keranjang.index') }}" class="btn btn-outline-primary position-relative">
                            <i class="bi bi-cart3 me-2"></i>Keranjang
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </a>
                        <a href="{{ route('list.peminjam') }}" class="btn btn-outline-info">
                            <i class="bi bi-people me-2"></i>List Peminjam
                        </a>
                        <a href="{{ route('cekStatus.form') }}" class="btn btn-outline-success">
                            <i class="bi bi-arrow-repeat me-2"></i>Pengembalian
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="dashboard-title mb-0"><i class="bi bi-journal-plus me-2"></i>Form Pengajuan Peminjaman</h1>
                <a href="{{ route('keranjang.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali ke Keranjang</a>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Formulir Peminjaman</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman.ajukan') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <!-- Foto Peminjam -->
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3"><i class="bi bi-camera me-2"></i>Foto Peminjam</h6>
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <div class="mb-3">
                                                        <img id="preview-foto" src="{{ asset('storage/dummy.jpg') }}" alt="Preview Foto" class="img-fluid rounded" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                                    </div>
                                                    <label for="foto_peminjam" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-upload me-2"></i>Pilih Foto
                                                    </label>
                                                    <input type="file" id="foto_peminjam" name="foto_peminjam" class="form-control d-none" accept="image/jpg,image/jpeg,image/png" required onchange="previewFoto(this)">
                                                    <div class="form-text">Format: JPG, JPEG, PNG (Max: 2MB)</div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                                        <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}" placeholder="Masukkan nama lengkap">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Unit / Jurusan</label>
                                                        <input type="text" name="unit" class="form-control" required value="{{ old('unit') }}" placeholder="Contoh: Teknik Informatika">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">No. Telepon</label>
                                                        <input type="text" name="no_telp" class="form-control" required value="{{ old('no_telp') }}" placeholder="Contoh: 08123456789">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Nama Kegiatan</label>
                                                        <input type="text" name="nama_kegiatan" class="form-control" required value="{{ old('nama_kegiatan') }}" placeholder="Contoh: Seminar Teknologi">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informasi Peminjaman -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Dari Tanggal</label>
                                <input type="date" name="tanggal_mulai" class="form-control" required value="{{ old('tanggal_mulai') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sampai Tanggal</label>
                                <input type="date" name="tanggal_selesai" class="form-control" required value="{{ old('tanggal_selesai') }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Lampiran Bukti (PDF/JPG/PNG)</label>
                                <input type="file" name="bukti" class="form-control" accept="application/pdf,image/jpeg,image/png" required>
                                <div class="form-text">Upload bukti kegiatan atau surat pengantar</div>
                            </div>
                        </div>
                        
                        <h5 class="mb-3"><i class="bi bi-box-seam me-2"></i>Barang yang Dipinjam:</h5>
                        <div class="table-responsive mb-3">
                            <table class="table align-middle table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td><span class="badge bg-primary">{{ $item['qty'] }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success"><i class="bi bi-send-check"></i> Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-foto').src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Set default date values
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    const tanggalMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
    const tanggalSelesaiInput = document.querySelector('input[name="tanggal_selesai"]');
    
    if (tanggalMulaiInput && !tanggalMulaiInput.value) {
        tanggalMulaiInput.value = today;
    }
    
    if (tanggalSelesaiInput && !tanggalSelesaiInput.value) {
        tanggalSelesaiInput.value = tomorrowStr;
    }
});
</script>
@endpush 