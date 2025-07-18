@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Tambah Barang Inventaris</h2>
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
<form action="{{ route('admin.inventaris.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
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
            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi') }}</textarea>
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label fw-bold">Foto Barang <span class="text-danger">(wajib 3 foto, jpg/png)</span></label>
            <input type="file" name="foto[]" class="form-control" accept="image/*" multiple required onchange="previewImages(event)" id="fotoInput">
            <div class="form-text">Pilih 3 foto sekaligus dengan Ctrl/Shift atau drag & drop (preview otomatis muncul di bawah)</div>
            <div class="mt-2 d-flex gap-2" id="preview"></div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-biru">Simpan</button>
    </div>
</form>
<script>
function previewImages(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    const files = event.target.files;
    if(files.length !== 3) {
        alert('Wajib upload 3 foto!');
        event.target.value = '';
        return;
    }
    Array.from(files).slice(0,3).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100px';
            img.style.maxHeight = '100px';
            img.style.borderRadius = '0.5rem';
            img.style.objectFit = 'cover';
            img.style.border = '1px solid #ddd';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endsection 