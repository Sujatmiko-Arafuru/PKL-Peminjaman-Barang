@extends('admin.layouts.app')

@section('content')
<style>
.photo-upload-container {
    position: relative;
    min-height: 150px;
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.photo-upload-placeholder {
    text-align: center;
    width: 100%;
}

.photo-preview {
    position: relative;
    width: 100%;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.photo-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.remove-photo {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    z-index: 10;
}
</style>
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
            <label class="form-label fw-bold">Foto Barang (Maksimal 3 foto)</label>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="photo-upload-container" id="photo1-container">
                        <div class="photo-upload-placeholder">
                            <input type="file" name="photo1" class="form-control photo-input" accept="image/*" data-photo-num="1">
                            <small class="text-muted">Upload Foto 1</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="photo-upload-container" id="photo2-container">
                        <div class="photo-upload-placeholder">
                            <input type="file" name="photo2" class="form-control photo-input" accept="image/*" data-photo-num="2">
                            <small class="text-muted">Upload Foto 2</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="photo-upload-container" id="photo3-container">
                        <div class="photo-upload-placeholder">
                            <input type="file" name="photo3" class="form-control photo-input" accept="image/*" data-photo-num="3">
                            <small class="text-muted">Upload Foto 3</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">Foto yang diupload: <span id="photo-count">0</span>/3</small>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-biru">Simpan</button>
    </div>
</form>

<script>
// Handle photo upload preview
document.querySelectorAll('.photo-input').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = input.closest('.photo-upload-container');
                const placeholder = container.querySelector('.photo-upload-placeholder');
                
                // Create preview
                const preview = document.createElement('div');
                preview.className = 'photo-preview';
                preview.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger remove-photo">×</button>
                `;
                
                // Replace placeholder with preview
                placeholder.style.display = 'none';
                container.appendChild(preview);
                
                // Update photo count
                updatePhotoCount();
                
                // Add remove functionality
                preview.querySelector('.remove-photo').addEventListener('click', function() {
                    container.removeChild(preview);
                    placeholder.style.display = 'block';
                    input.value = '';
                    updatePhotoCount();
                });
            };
            reader.readAsDataURL(file);
        }
    });
});

// Update photo count
function updatePhotoCount() {
    const photoCount = document.querySelectorAll('.photo-preview').length;
    document.getElementById('photo-count').textContent = photoCount;
}

// Handle form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const photoInputs = document.querySelectorAll('.photo-input');
    let hasPhoto = false;
    
    photoInputs.forEach(input => {
        if (input.files.length > 0) {
            hasPhoto = true;
        }
    });
    
    if (hasPhoto) {
        // Check if total photos exceed 3
        const photoCount = document.querySelectorAll('.photo-preview').length;
        if (photoCount > 3) {
            alert('Maksimal 3 foto per barang!');
            e.preventDefault();
            return;
        }
    }
});
</script>

@endsection 