@extends('admin.layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/photo-gallery.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-box-seam me-2"></i>Tambah Barang Inventaris
            </h2>
            <p class="text-muted mb-0">Tambah barang baru ke dalam sistem inventaris</p>
        </div>
        <div>
            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Inventaris
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-plus-circle me-2"></i>Form Tambah Barang
                    </h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.inventaris.store') }}" method="POST" class="mb-4" id="createForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Barang</label>
                                <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jumlah Stok</label>
                                <input type="number" name="stok" class="form-control" min="0" required value="{{ old('stok') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="tersedia" {{ old('status')=='tersedia'?'selected':'' }}>Tersedia</option>
                                    <option value="tidak tersedia" {{ old('status')=='tidak tersedia'?'selected':'' }}>Tidak Tersedia</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi barang...">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>

                        <!-- Foto Upload Section -->
                        <div class="photo-upload-section mb-4">
                            <h6 class="text-primary fw-semibold mb-3">
                                <i class="bi bi-camera me-2"></i>Foto Barang (Maksimal 3 foto)
                            </h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Foto 1</label>
                                    <input type="file" name="foto1" class="form-control" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview1')">
                                    <div class="mt-2">
                                        <img id="preview1" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 1" class="photo-preview" style="max-width: 100%; height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Foto 2</label>
                                    <input type="file" name="foto2" class="form-control" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview2')">
                                    <div class="mt-2">
                                        <img id="preview2" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 2" class="photo-preview" style="max-width: 100%; height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Foto 3</label>
                                    <input type="file" name="foto3" class="form-control" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview3')">
                                    <div class="mt-2">
                                        <img id="preview3" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 3" class="photo-preview" style="max-width: 100%; height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran: 2MB per foto.
                            </small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Informasi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">
                            <i class="bi bi-lightbulb me-2"></i>Tips Pengisian
                        </h6>
                        <ul class="mb-0">
                            <li>Isi nama barang dengan jelas dan spesifik</li>
                            <li>Stok harus berupa angka positif</li>
                            <li>Deskripsi membantu identifikasi barang</li>
                            <li>Status akan otomatis diupdate berdasarkan stok</li>
                            <li>Foto barang membantu identifikasi visual</li>
                            <li>Upload maksimal 3 foto dengan format JPG/PNG</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection 