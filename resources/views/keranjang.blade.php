@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-cart3 me-2"></i>Keranjang Peminjaman</h1>
            
            @if(session('kode_peminjaman'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-receipt me-2"></i>
                    <strong>Kode Peminjaman Anda:</strong> 
                    <span class="badge bg-dark ms-2">{{ session('kode_peminjaman') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(count($cleanedCart) > 0)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Barang di Keranjang</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Tersedia</th>
                                    <th>Stok Dipinjam</th>
                                    <th>Status</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cleanedCart as $item)
                                <tr>
                                    <td style="width:80px">
                                        @php
                                            $fotoUtama = null;
                                            if(isset($item['foto'])) {
                                                if(is_array($item['foto'])) {
                                                    $fotoUtama = count($item['foto']) > 0 ? $item['foto'][0] : null;
                                                } else {
                                                    $fotoUtama = $item['foto'];
                                                }
                                            }
                                        @endphp
                                        @if($fotoUtama)
                                        <img src="{{ asset('storage/' . $fotoUtama) }}" alt="{{ $item['nama'] }}" class="rounded" style="max-width:60px;max-height:60px;">
                                        @else
                                        <span class="text-muted"><i class="bi bi-image"></i></span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $item['nama'] }}</td>
                                    <td><span class="badge bg-success">{{ $item['stok_tersedia'] ?? 0 }}</span></td>
                                    <td><span class="badge bg-warning text-dark">{{ $item['stok_dipinjam'] ?? 0 }}</span></td>
                                    <td><span class="badge {{ $item['status'] == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($item['status']) }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="updateQty({{ $item['id'] }}, 'decrease')" {{ $item['qty'] <= 1 ? 'disabled' : '' }}>
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <span class="fw-bold" id="qty-{{ $item['id'] }}">{{ $item['qty'] }}</span>
                                                                                         <button class="btn btn-outline-secondary btn-sm ms-2" onclick="updateQty({{ $item['id'] }}, 'increase')" {{ $item['qty'] >= ($item['stok_tersedia'] ?? $item['stok']) ? 'disabled' : '' }}>
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('keranjang.hapus', $item['id']) }}" method="POST" onsubmit="return confirm('Hapus barang dari keranjang?')">
                                            @csrf
                                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('peminjaman.form') }}" class="btn btn-success"><i class="bi bi-arrow-right-circle"></i> Lanjutkan Peminjaman</a>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i> Keranjang masih kosong.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateQty(itemId, action) {
    // Disable button selama proses
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    
    fetch(`/keranjang/update-qty/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            action: action
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
        if (data.success) {
            if (data.removed) {
                // Item dihapus dari keranjang, reload halaman
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }
            
            // Update the quantity display
            document.getElementById(`qty-${itemId}`).textContent = data.newQty;
            
            // Update button states
            const decreaseBtn = document.querySelector(`button[onclick="updateQty(${itemId}, 'decrease')"]`);
            const increaseBtn = document.querySelector(`button[onclick="updateQty(${itemId}, 'increase')"]`);
            
            // Disable/enable decrease button
            if (data.newQty <= 1) {
                decreaseBtn.disabled = true;
            } else {
                decreaseBtn.disabled = false;
            }
            
            // Disable/enable increase button based on stock
            if (data.newQty >= data.stock) {
                increaseBtn.disabled = true;
            } else {
                increaseBtn.disabled = false;
            }
            
            // Show success message
            showAlert('success', data.message);
        } else {
            throw new Error(data.message || 'Gagal memperbarui jumlah');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Jika error terkait CSRF, refresh halaman
        if (error.message.includes('CSRF') || error.message.includes('non-JSON')) {
            showAlert('danger', 'Session telah berakhir. Halaman akan di-refresh...');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showAlert('danger', 'Terjadi kesalahan saat memperbarui jumlah: ' + error.message);
        }
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the top of the main content area
    const mainContent = document.querySelector('.col-md-9.col-lg-10');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}
</script>
@endpush 