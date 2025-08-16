@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-arrow-clockwise me-2"></i>Detail Pengembalian
            </h2>
            <p class="text-muted mb-0">Detail pengembalian barang peminjaman</p>
        </div>
        <div>
            <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

<div class="row">
        <!-- Data Peminjam -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-primary">
                        <i class="bi bi-person me-2"></i>Data Peminjam
                    </h6>
                </div>
            <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Nama</small>
                        <div class="fw-semibold">{{ $peminjaman->nama }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">No HP</small>
                        <div class="fw-semibold">{{ $peminjaman->no_telp }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Unit/Jurusan</small>
                        <div class="fw-semibold">{{ $peminjaman->unit }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Nama Kegiatan</small>
                        <div class="fw-semibold">{{ $peminjaman->nama_kegiatan }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Periode Peminjaman</small>
                        <div class="fw-semibold">
                            {{ format_tanggal($peminjaman->tanggal_mulai) }} - 
                            {{ format_tanggal($peminjaman->tanggal_selesai) }}
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Kode Peminjaman</small>
                        <div class="fw-semibold text-primary">{{ $peminjaman->kode_peminjaman }}</div>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Status</small>
                        <div>
                            @if($peminjaman->status == 'pengembalian_diajukan')
                                <span class="badge bg-warning text-dark rounded-pill">
                                    <i class="bi bi-clock me-1"></i>Pengembalian Diajukan
                                </span>
                            @elseif($peminjaman->status == 'disetujui')
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i>Disetujui
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang yang Dipinjam -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-primary">
                        <i class="bi bi-box-seam me-2"></i>Barang yang Dipinjam
                    </h6>
                </div>
                <div class="card-body">
                    @if($peminjaman->details->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($peminjaman->details as $detail)
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-2">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $detail->barang->nama ?? '-' }}</div>
                                    <small class="text-muted">ID: {{ $detail->barang->id ?? '-' }}</small>
                                    <div class="mt-1">
                                        <small class="text-muted">Dipinjam: {{ $detail->jumlah }}</small>
                                        @if($detail->jumlah_dikembalikan)
                                            <br><small class="text-success">Dikembalikan: {{ $detail->jumlah_dikembalikan }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($peminjaman->status == 'pengembalian_diajukan')
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Jumlah Dikembalikan</label>
                                            <input type="number" 
                                                   class="form-control form-control-sm jumlah-dikembalikan" 
                                                   data-detail-id="{{ $detail->id }}"
                                                   data-peminjaman-id="{{ $peminjaman->id }}"
                                                   min="0" 
                                                   max="{{ $detail->jumlah }}" 
                                                   value="{{ $detail->jumlah_dikembalikan ?? 0 }}"
                                                   style="width: 80px;">
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary update-detail-btn"
                                                data-detail-id="{{ $detail->id }}"
                                                data-peminjaman-id="{{ $peminjaman->id }}">
                                            <i class="bi bi-check-lg"></i> Update
                                        </button>
                                    @else
                                        <span class="badge bg-primary rounded-pill">{{ $detail->jumlah }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-box-seam fs-1"></i>
                            <p class="mb-0 mt-2">Tidak ada barang</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    @if($peminjaman->status == 'pengembalian_diajukan')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-info-circle me-2"></i>Aksi Pengembalian
                    </h6>
                    <div class="d-flex gap-2 justify-content-center">
                        <form action="{{ route('admin.pengembalian.approve', $peminjaman->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success shadow-sm" 
                                    onclick="return confirm('Approve pengembalian ini?')">
                                <i class="bi bi-check-lg me-2"></i>Approve Pengembalian
                            </button>
                        </form>
                        <form action="{{ route('admin.pengembalian.reject', $peminjaman->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-danger shadow-sm" 
                                    onclick="return confirm('Tolak pengembalian ini?')">
                                <i class="bi bi-x-lg me-2"></i>Reject Pengembalian
                            </button>
                        </form>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Catatan:</strong> Pastikan semua barang sudah dicek dan jumlah yang dikembalikan sudah benar sebelum approve.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.card {
    border-radius: 0.75rem;
}

.badge {
    font-size: 0.75rem;
}

.btn {
    border-radius: 0.5rem;
}

.list-group-item {
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #20B2AA;
    box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
}

.alert {
    border-radius: 0.5rem;
    border: none;
}

.alert-success {
    background: linear-gradient(135deg, #2E8B57, #3CB371);
    color: white;
}

.alert-danger {
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle update detail pengembalian
    const updateButtons = document.querySelectorAll('.update-detail-btn');
    
    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const detailId = this.getAttribute('data-detail-id');
            const peminjamanId = this.getAttribute('data-peminjaman-id');
            const input = document.querySelector(`input[data-detail-id="${detailId}"]`);
            const jumlahDikembalikan = input.value;
            
            if (jumlahDikembalikan < 0 || jumlahDikembalikan > parseInt(input.getAttribute('max'))) {
                alert('Jumlah yang dikembalikan tidak valid!');
                return;
            }
            
            // Disable button during request
            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';
            
            // Send AJAX request
            fetch(`/admin/pengembalian/${peminjamanId}/update-detail`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    detail_id: detailId,
                    jumlah_dikembalikan: jumlahDikembalikan
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <i class="bi bi-check-circle me-2"></i>${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row'));
                    
                    // Update button text
                    this.innerHTML = '<i class="bi bi-check-lg"></i> Updated';
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-success');
                    
                    // Auto-hide alert after 3 seconds
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 3000);
                } else {
                    alert(data.message || 'Terjadi kesalahan saat update');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat update detail pengembalian');
            })
            .finally(() => {
                // Re-enable button
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-check-lg"></i> Update';
            });
        });
    });
    
    // Add CSRF token meta tag if not exists
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = document.querySelector('input[name="_token"]').value;
        document.head.appendChild(meta);
    }
});
</script>
@endsection 