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

.delete-photo {
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
            <label class="form-label fw-bold">Foto Barang (Maksimal 3 foto)</label>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="photo-upload-container" id="photo1-container">
                        @if($barang->foto)
                            <div class="photo-preview">
                                <img src="{{ Storage::url('public/barang-photos/' . $barang->foto) }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger delete-photo" data-barang-id="{{ $barang->id }}" data-photo-column="foto" style="position: absolute; top: 5px; right: 5px;">×</button>
                            </div>
                        @else
                            <div class="photo-upload-placeholder">
                                <input type="file" name="photo1" class="form-control photo-input" accept="image/*">
                                <small class="text-muted">Upload Foto 1</small>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="photo-upload-container" id="photo2-container">
                        @if($barang->foto2)
                            <div class="photo-preview">
                                <img src="{{ Storage::url('public/barang-photos/' . $barang->foto2) }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger delete-photo" data-barang-id="{{ $barang->id }}" data-photo-column="foto2" style="position: absolute; top: 5px; right: 5px;">×</button>
                            </div>
                        @else
                            <div class="photo-upload-placeholder">
                                <input type="file" name="photo2" class="form-control photo-input" accept="image/*">
                                <small class="text-muted">Upload Foto 2</small>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="photo-upload-container" id="photo3-container">
                        @if($barang->foto3)
                            <div class="photo-preview">
                                <img src="{{ Storage::url('public/barang-photos/' . $barang->foto3) }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger delete-photo" data-barang-id="{{ $barang->id }}" data-photo-column="foto3" style="position: absolute; top: 5px; right: 5px;">×</button>
                            </div>
                        @else
                            <div class="photo-upload-placeholder">
                                <input type="file" name="photo3" class="form-control photo-input" accept="image/*">
                                <small class="text-muted">Upload Foto 3</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">Foto yang diupload: {{ $barang->getPhotoCount() }}/3</small>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-biru">Update</button>
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
                
                // Create preview element
                const preview = document.createElement('div');
                preview.className = 'photo-preview';
                preview.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger remove-photo" style="position: absolute; top: 5px; right: 5px;">×</button>
                `;
                
                // Replace placeholder with preview
                placeholder.style.display = 'none';
                container.appendChild(preview);
                
                // Add remove functionality
                preview.querySelector('.remove-photo').addEventListener('click', function() {
                    container.removeChild(preview);
                    placeholder.style.display = 'block';
                    input.value = '';
                });
            };
            reader.readAsDataURL(file);
        }
    });
});

// Handle delete photo via AJAX
document.querySelectorAll('.delete-photo').forEach(button => {
    button.addEventListener('click', function() {
        if (confirm('Yakin ingin menghapus foto ini?')) {
            const barangId = this.dataset.barangId;
            const photoColumn = this.dataset.photoColumn;
            
            fetch(`/admin/barang/${barangId}/delete-photo`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    photo_column: photoColumn
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page to show updated state
                    location.reload();
                } else {
                    alert('Gagal menghapus foto: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus foto');
            });
        }
    });
});

// Handle form submission for photo uploads
document.getElementById('editForm').addEventListener('submit', function(e) {
    const photoInputs = document.querySelectorAll('.photo-input');
    let hasPhoto = false;
    
    photoInputs.forEach(input => {
        if (input.files.length > 0) {
            hasPhoto = true;
        }
    });
    
    if (hasPhoto) {
        // Upload photos via AJAX first
        e.preventDefault();
        
        const formData = new FormData();
        photoInputs.forEach((input, index) => {
            if (input.files.length > 0) {
                formData.append('photo', input.files[0]);
            }
        });
        
        fetch(`/admin/barang/{{ $barang->id }}/upload-photo`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Submit the form normally
                document.getElementById('editForm').submit();
            } else {
                alert('Gagal upload foto: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat upload foto');
        });
    }
});
</script>
@endsection 