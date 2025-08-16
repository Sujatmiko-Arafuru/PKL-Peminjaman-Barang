@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/photo-gallery.css') }}">
@endsection

@section('content')
<style>
    .btn:disabled {
        opacity: 0.6 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }
    
    .btn-secondary:disabled {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #fff !important;
    }
    
    .btn-secondary:disabled:hover {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
    
    /* Tombol tambah yang aktif */
    .btn-success {
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    /* Modal styling */
    .modal-content {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .modal-header {
        border-bottom: 2px solid #e9ecef;
        background: linear-gradient(135deg, #20B2AA, #48D1CC);
        color: white;
        border-radius: 1rem 1rem 0 0;
    }
    
    .modal-footer {
        border-top: 2px solid #e9ecef;
        background: #E0FFFF;
        border-radius: 0 0 1rem 1rem;
    }
    
    /* Notification styling */
    .alert.position-fixed {
        animation: slideInRight 0.5s ease-out;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
        border-radius: 0.5rem;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .alert-success {
        background: linear-gradient(135deg, #2E8B57, #3CB371);
        color: white;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
        color: white;
    }
    
    .alert .btn-close {
        filter: invert(1);
    }

    /* Photo carousel styling */
    .photo-carousel {
        height: 180px;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .photo-carousel .carousel-item img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .photo-placeholder {
        height: 180px;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            @if(session('success'))
                @php
                    $successMessage = session('success');
                    $kodePeminjaman = null;
                    if (strpos($successMessage, 'Kode Peminjaman:') !== false) {
                        $kodePeminjaman = trim(substr($successMessage, strpos($successMessage, 'Kode Peminjaman:') + strlen('Kode Peminjaman:')));
                        $successMessage = trim(substr($successMessage, 0, strpos($successMessage, 'Kode Peminjaman:')));
                    }
                @endphp
                
                @if($kodePeminjaman)
                    <!-- Modal Kode Peminjaman -->
                    <div class="modal fade" id="modalKodePeminjaman" tabindex="-1" aria-labelledby="modalKodePeminjamanLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="modalKodePeminjamanLabel">
                                        <i class="bi bi-check-circle me-2"></i>Peminjaman Berhasil!
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="mb-4">
                                        <i class="bi bi-receipt text-success" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="text-muted mb-3">Kode Peminjaman Anda:</h6>
                                    <div class="bg-light p-3 rounded mb-3">
                                        <h4 class="text-primary fw-bold mb-0" id="kodePeminjamanText">{{ $kodePeminjaman }}</h4>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyKodePeminjaman()">
                                        <i class="bi bi-clipboard me-2"></i>Salin Kode
                                    </button>
                                    <p class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Simpan kode ini untuk mengecek status peminjaman Anda
                                    </p>
                                    <p class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Format: NAMA-TANGGAL-URUTAN
                                    </p>
                                    <div class="alert alert-info">
                                        <i class="bi bi-lightbulb me-2"></i>
                                        <strong>Tips:</strong> Anda dapat menggunakan kode ini untuk mencari peminjaman di menu "Pengembalian Barang"
                                        <br><small class="text-muted">Contoh: JOH-20241201-0001, SAR-20241201-0002, MIK-20241201-0003, ANA-20241201-0004, DAV-20241201-0005, EMM-20241201-0006, JAM-20241201-0007</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                                        <i class="bi bi-check me-2"></i>Mengerti
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var modal = new bootstrap.Modal(document.getElementById('modalKodePeminjaman'));
                            modal.show();
                        });
                        
                        function copyKodePeminjaman() {
                            const kodeText = document.getElementById('kodePeminjamanText').textContent;
                            navigator.clipboard.writeText(kodeText).then(function() {
                                // Tampilkan notifikasi sukses
                                const button = event.target.closest('button');
                                const originalText = button.innerHTML;
                                button.innerHTML = '<i class="bi bi-check me-2"></i>Tersalin!';
                                button.classList.remove('btn-outline-primary');
                                button.classList.add('btn-success');
                                
                                setTimeout(() => {
                                    button.innerHTML = originalText;
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-outline-primary');
                                }, 2000);
                            }).catch(function(err) {
                                console.error('Gagal menyalin: ', err);
                                alert('Gagal menyalin kode ke clipboard');
                            });
                        }
                    </script>
                @else
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ $successMessage }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="dashboard-title mb-0">Daftar Barang Tersedia</h1>
            </div>
            
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Format Kode:</strong> NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)
            </div>
            
            <!-- Search Form -->
            <form method="GET" action="/" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama barang..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                </div>
            </form>
            
            <!-- Barang List -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse($barangs as $barang)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- Photo Section -->
                        @if($barang->hasPhotos())
                            @if($barang->photo_count > 1)
                                <div id="photoCarousel{{ $barang->id }}" class="carousel slide photo-carousel photo-gallery" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        @foreach($barang->photos as $index => $photo)
                                        <button type="button" data-bs-target="#photoCarousel{{ $barang->id }}" data-bs-slide-to="{{ $index }}" 
                                                class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                                aria-label="Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                    </div>
                                    <div class="carousel-inner">
                                        @foreach($barang->photos as $index => $photo)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ Storage::url($photo) }}" class="d-block w-100" alt="Foto {{ $index + 1 }}">
                                        </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel{{ $barang->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel{{ $barang->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            @else
                                <img src="{{ Storage::url($barang->main_photo) }}" alt="{{ $barang->nama }}" class="photo-carousel" style="object-fit: cover;">
                            @endif
                        @else
                            <div class="photo-placeholder">
                                <i class="bi bi-box-seam text-secondary" style="font-size:2.5rem;"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary mb-1">{{ $barang->nama }}</h5>
                            <p class="card-text mb-1">{{ Str::limit($barang->deskripsi, 60) }}</p>
                            <p class="mb-1">Stok Tersedia: <span class="fw-bold text-success">{{ $barang->stok_tersedia }}</span></p>
                            <p class="mb-1">Stok Dipinjam: <span class="fw-bold text-warning">{{ $barang->stok_dipinjam }}</span></p>
                            <p>Status: <span class="badge {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($barang->status) }}</span></p>
                            @if($barang->status !== 'tersedia')
                                <small class="text-muted">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Tidak tersedia untuk dipinjam
                                </small>
                            @endif
                            <div class="mt-auto d-flex gap-2">
                                <a href="{{ route('barang.detail', $barang->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-info-circle"></i> Detail</a>
                                <button class="btn btn-sm {{ $barang->status === 'tersedia' ? 'btn-success' : 'btn-secondary' }}" 
                                        {{ $barang->status !== 'tersedia' ? 'disabled' : '' }}
                                        style="{{ $barang->status !== 'tersedia' ? 'opacity: 0.6; cursor: not-allowed;' : '' }}"
                                        data-id="{{ $barang->id }}"
                                        data-nama="{{ $barang->nama }}"
                                        data-deskripsi="{{ $barang->deskripsi }}"
                                        data-stok="{{ $barang->stok_tersedia }}"
                                        data-status="{{ $barang->status }}"
                                        title="{{ $barang->status !== 'tersedia' ? 'Barang tidak tersedia untuk dipinjam (Stok: ' . $barang->stok_tersedia . ')' : 'Klik untuk menambah ke keranjang' }}">
                                    <i class="bi bi-cart-plus"></i> 
                                    {{ $barang->status === 'tersedia' ? 'Tambah' : 'Tidak Tersedia' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">Barang tidak ditemukan.</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah ke Keranjang -->
<div class="modal fade" id="modalTambahKeranjang" tabindex="-1" aria-labelledby="modalTambahKeranjangLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahKeranjangLabel">Tambah ke Keranjang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-box-seam text-secondary" style="font-size: 2.5rem;"></i>
          </div>
        </div>
        <h5 id="modalNamaBarang" class="text-primary mb-2"></h5>
        <div class="mb-2"><span id="modalDeskripsiBarang"></span></div>
        <div class="mb-2">Stok: <span id="modalStokBarang" class="fw-bold"></span></div>
        <div class="mb-2">Status: <span id="modalStatusBarang" class="badge"></span></div>
        <div class="mb-2">
          <label class="form-label">Jumlah</label>
          <input type="number" id="modalJumlahBarang" class="form-control" min="1" value="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" id="btnKonfirmasiTambah">Tambah ke Keranjang</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectedBarang = {};
        var modalTambah = new bootstrap.Modal(document.getElementById('modalTambahKeranjang'));
        
        // Inisialisasi tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Event listener untuk tombol tambah
        document.addEventListener('click', function(e) {
            if (e.target.closest('button[data-status="tersedia"]')) {
                const btn = e.target.closest('button[data-status="tersedia"]');
                if (btn.getAttribute('data-status') === 'tersedia') {
                    selectedBarang = {
                        id: btn.getAttribute('data-id'),
                        nama: btn.getAttribute('data-nama'),
                        deskripsi: btn.getAttribute('data-deskripsi'),
                        stok: btn.getAttribute('data-stok'),
                        status: btn.getAttribute('data-status')
                    };
                    document.getElementById('modalNamaBarang').innerText = selectedBarang.nama;
                    document.getElementById('modalDeskripsiBarang').innerText = selectedBarang.deskripsi;
                    document.getElementById('modalStokBarang').innerText = selectedBarang.stok;
                    let statusSpan = document.getElementById('modalStatusBarang');
                    statusSpan.innerText = selectedBarang.status.charAt(0).toUpperCase() + selectedBarang.status.slice(1);
                    statusSpan.className = 'badge ' + (selectedBarang.status === 'tersedia' ? 'bg-success' : 'bg-secondary');
                    let jumlahInput = document.getElementById('modalJumlahBarang');
                    jumlahInput.value = 1;
                    jumlahInput.max = selectedBarang.stok;
                    modalTambah.show();
                }
            }
        });
        
        document.getElementById('btnKonfirmasiTambah').addEventListener('click', function() {
            let jumlah = parseInt(document.getElementById('modalJumlahBarang').value);
            if(isNaN(jumlah) || jumlah < 1) jumlah = 1;
            if(jumlah > parseInt(selectedBarang.stok)) jumlah = parseInt(selectedBarang.stok);
            
            // Disable button selama proses
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
            
            // Ambil CSRF token dari meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch("{{ route('keranjang.tambah') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    barang_id: selectedBarang.id, 
                    jumlah: jumlah 
                })
            })
            .then(response => {
                // Cek content type untuk memastikan response adalah JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server mengembalikan response non-JSON. Kemungkinan ada masalah dengan CSRF token atau session.');
                }
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.json();
            })
            .then(data => {
                if(data.success) {
                    // Update cart count
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.innerText = data.cart_count;
                    }
                    modalTambah.hide();
                    
                    // Tampilkan notifikasi sukses yang lebih baik
                    showSuccessNotification('Barang "' + selectedBarang.nama + '" ('+jumlah+') berhasil ditambahkan ke keranjang!');
                } else {
                    throw new Error(data.message || 'Gagal menambahkan ke keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Jika error terkait CSRF, refresh halaman
                if (error.message.includes('CSRF') || error.message.includes('non-JSON')) {
                    showErrorNotification('Session telah berakhir. Halaman akan di-refresh...');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showErrorNotification('Terjadi kesalahan saat menambahkan ke keranjang: ' + error.message);
                }
            })
            .finally(() => {
                // Re-enable button
                btn.disabled = false;
                btn.innerHTML = 'Tambah ke Keranjang';
            });
        });
        
        // Fungsi untuk menampilkan notifikasi sukses
        function showSuccessNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'alert alert-success position-fixed';
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(notification);
            
            // Auto remove setelah 5 detik
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
        
        // Fungsi untuk menampilkan notifikasi error
        function showErrorNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'alert alert-danger position-fixed';
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(notification);
            
            // Auto remove setelah 8 detik
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 8000);
        }
    });
</script>
@endsection 