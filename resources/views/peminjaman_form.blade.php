@extends('layouts.app')

@section('content')
<style>
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

.form-control:focus {
    border-color: #20B2AA;
    box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
}
</style>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-file-earmark-text me-2"></i>Form Peminjaman</h1>
            
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Format Kode:</strong> NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)
            </div>
            
            @if(session('kode_peminjaman'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-receipt me-2"></i>
                    <strong>Kode Peminjaman Anda:</strong> 
                    <span class="badge bg-dark ms-2">{{ session('kode_peminjaman') }}</span>
                    <br><small class="text-muted">Format: NAMA-TANGGAL-URUTAN</small>
                    <br><small class="text-muted">Contoh: JOH-20241201-0001, SAR-20241201-0002, MIK-20241201-0003, ANA-20241201-0004, DAV-20241201-0005, EMM-20241201-0006, JAM-20241201-0007</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
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
                                                    <div class="mb-3">
                                                        <input type="file" id="foto_peminjam" name="foto_peminjam" class="form-control" accept="image/jpg,image/jpeg,image/png" required onchange="previewFoto(this)">
                                                        <div class="form-text">Format: JPG, JPEG, PNG (Max: 2MB)</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                                        <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" minlength="3">
                                                        <div class="form-text">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Minimal 3 karakter untuk generate kode unik (Format: NAMA-TANGGAL-URUTAN)
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">NIM/NIP</label>
                                                        <input type="text" name="nim_nip" class="form-control" required value="{{ old('nim_nip') }}" placeholder="Contoh: 2021001234 atau 19850101200101001">
                                                        <div class="form-text">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Masukkan NIM untuk mahasiswa atau NIP untuk dosen/staff
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Jurusan / Ormawa</label>
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
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-info" onclick="testForm()">
                                <i class="bi bi-bug"></i> Test Form
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="bi bi-send-check"></i> Ajukan Peminjaman
                            </button>
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

function testForm() {
    console.log('=== FORM TEST START ===');
    const form = document.querySelector('form[action*="peminjaman/ajukan"]');
    console.log('Form found:', !!form);
    console.log('Form action:', form ? form.action : 'N/A');
    console.log('Form method:', form ? form.method : 'N/A');
    console.log('Form enctype:', form ? form.enctype : 'N/A');
    
    // Check all form fields
    const fields = form ? form.querySelectorAll('input, select, textarea') : [];
    console.log('Total form fields:', fields.length);
    
    fields.forEach((field, index) => {
        console.log(`Field ${index + 1}:`, {
            name: field.name,
            type: field.type,
            value: field.value,
            required: field.required,
            disabled: field.disabled
        });
    });
    
    // Check cart
    const cartTable = document.querySelector('table tbody');
    console.log('Cart table found:', !!cartTable);
    console.log('Cart items:', cartTable ? cartTable.children.length : 0);
    
    console.log('=== FORM TEST END ===');
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
    
    // Form validation and debugging
    const form = document.querySelector('form[action*="peminjaman/ajukan"]');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        console.log('Form found:', form);
        
        form.addEventListener('submit', function(e) {
            console.log('Form submission started...');
            
            // Disable submit button to prevent double submission
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
            }
            
            // Check if all required fields are filled
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let emptyFields = [];
            
            requiredFields.forEach(function(field) {
                console.log('Checking field:', field.name, 'Value:', field.value);
                if (!field.value.trim()) {
                    console.error('Required field empty:', field.name);
                    emptyFields.push(field.name);
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Check if cart has items
            const cartTable = document.querySelector('table tbody');
            if (cartTable && cartTable.children.length === 0) {
                console.error('Cart is empty');
                alert('Keranjang kosong! Silakan tambahkan barang terlebih dahulu.');
                e.preventDefault();
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send-check"></i> Ajukan Peminjaman';
                }
                return false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi: ' + emptyFields.join(', '));
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send-check"></i> Ajukan Peminjaman';
                }
                return false;
            }
            
            console.log('Form validation passed, submitting...');
            // Allow form to submit
        });
    } else {
        console.error('Form not found!');
    }
});
</script>
@endpush 