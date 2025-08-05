@extends('layouts.app')

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
        background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        color: white;
        border-radius: 1rem 1rem 0 0;
    }
    
    .modal-footer {
        border-top: 2px solid #e9ecef;
        background: #f8f9fa;
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
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
        color: white;
    }
    
    .alert .btn-close {
        filter: invert(1);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="dashboard-title mb-0">Daftar Barang Tersedia</h1>
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
                        @php
                            $foto = null;
                            if ($barang->foto) {
                                $fotoArr = json_decode($barang->foto, true);
                                if (is_array($fotoArr) && count($fotoArr) > 0) {
                                    $foto = $fotoArr[0];
                                }
                            }
                        @endphp
                        @if($foto)
                        <img src="{{ asset('storage/' . $foto) }}" alt="{{ $barang->nama }}" class="card-img-top" style="max-height:180px;object-fit:cover;">
                        @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:180px;border-radius:.5rem;">
                            <i class="bi bi-image text-secondary" style="font-size:2.5rem;"></i>
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
                                        data-foto="{{ $foto ? asset('storage/' . $foto) : '' }}"
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
          <img id="modalFotoBarang" src="" alt="Foto" style="max-width:180px;max-height:180px;border-radius:0.5rem;object-fit:cover;">
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
                        foto: btn.getAttribute('data-foto'),
                        deskripsi: btn.getAttribute('data-deskripsi'),
                        stok: btn.getAttribute('data-stok'),
                        status: btn.getAttribute('data-status')
                    };
                    document.getElementById('modalFotoBarang').src = selectedBarang.foto;
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