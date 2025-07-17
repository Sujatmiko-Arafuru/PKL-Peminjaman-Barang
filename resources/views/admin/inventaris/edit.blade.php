@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Edit Barang Inventaris</h2>
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
<form action="{{ route('admin.inventaris.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="mb-4" id="editForm">
    @csrf
    @method('PUT')
    <div class="row mb-3">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Nama Barang</label>
            <input type="text" name="nama" class="form-control" required value="{{ old('nama', $barang->nama) }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Jumlah Stok</label>
            <input type="number" name="stok" class="form-control" min="0" required value="{{ old('stok', $barang->stok) }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select" required>
                <option value="tersedia" {{ old('status', $barang->status)=='tersedia'?'selected':'' }}>Tersedia</option>
                <option value="tidak tersedia" {{ old('status', $barang->status)=='tidak tersedia'?'selected':'' }}>Tidak Tersedia</option>
            </select>
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label fw-bold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label fw-bold">Foto Barang (max 4, jpg/png)</label>
            <input type="file" name="foto[]" class="form-control" accept="image/*" multiple onchange="previewImages(event)">
            <div class="mt-2" id="preview">
                @if($barang->foto && count(json_decode($barang->foto, true)) > 0)
                    @foreach(json_decode($barang->foto, true) as $idx => $foto)
                        <div class="d-inline-block position-relative me-2 mb-2 preview-old-foto" data-idx="{{ $idx }}">
                            <img src="{{ asset('storage/' . $foto) }}" style="max-width:80px;max-height:80px;border-radius:0.5rem;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" style="border-radius:50%;padding:2px 6px;font-size:12px;transform:translate(30%,-30%);" onclick="removeOldFoto({{ $idx }})">&times;</button>
                            <input type="hidden" name="keep_foto[]" value="{{ $foto }}">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-biru">Update</button>
    </div>
</form>
<script>
function previewImages(event) {
    const preview = document.getElementById('preview');
    // Hapus preview foto baru sebelumnya, tapi biarkan preview-old-foto
    Array.from(preview.querySelectorAll('.preview-new-foto')).forEach(e => e.remove());
    const files = event.target.files;
    if(files.length > 4 - preview.querySelectorAll('.preview-old-foto').length) {
        alert('Maksimal total 4 foto!');
        event.target.value = '';
        return;
    }
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '80px';
            img.style.maxHeight = '80px';
            img.style.marginRight = '8px';
            img.style.borderRadius = '0.5rem';
            const div = document.createElement('div');
            div.className = 'd-inline-block position-relative me-2 mb-2 preview-new-foto';
            div.appendChild(img);
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
function removeOldFoto(idx) {
    const el = document.querySelector('.preview-old-foto[data-idx="'+idx+'"]');
    if(el) el.remove();
}
document.getElementById('editForm').addEventListener('submit', function(e) {
    // Pastikan total foto tidak lebih dari 4
    const oldCount = document.querySelectorAll('.preview-old-foto').length;
    const newCount = document.querySelector('input[type=file][name="foto[]"]').files.length;
    if(oldCount + newCount > 4) {
        alert('Maksimal total 4 foto!');
        e.preventDefault();
    }
});
</script>
@endsection 