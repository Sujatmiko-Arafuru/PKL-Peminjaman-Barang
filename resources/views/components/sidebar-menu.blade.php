<style>
    .alert-sm {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
</style>

<div class="col-md-3 col-lg-2">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3"><i class="bi bi-list"></i> Menu</h5>
            <div class="d-grid gap-2">
                @if(session('kode_peminjaman'))
                    <div class="alert alert-success alert-sm mb-3">
                        <small>
                            <i class="bi bi-receipt me-1"></i>
                            <strong>Kode Anda:</strong><br>
                            <span class="badge bg-dark">{{ session('kode_peminjaman') }}</span>
                            <br><small class="text-muted">Format: NAMA-TANGGAL-URUTAN</small>
                            <br><small class="text-muted">Contoh: JOH-20241201-0001, SAR-20241201-0002, MIK-20241201-0003, ANA-20241201-0004, DAV-20241201-0005, EMM-20241201-0006, JAM-20241201-0007</small>
                        </small>
                    </div>
                @endif
                
                <a href="{{ route('beranda') }}" class="btn {{ request()->routeIs('beranda') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-house me-2"></i>Beranda
                </a>
                <a href="{{ route('dashboard') }}" class="btn {{ request()->routeIs('dashboard') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-box-seam me-2"></i>List Barang
                </a>
                <a href="{{ route('keranjang.index') }}" class="btn {{ request()->routeIs('keranjang.index') ? 'btn-primary' : 'btn-outline-primary' }} position-relative">
                    <i class="bi bi-cart3 me-2"></i>Keranjang
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </a>
                <a href="{{ route('list.peminjam') }}" class="btn {{ request()->routeIs('list.peminjam*') ? 'btn-info' : 'btn-outline-info' }}">
                    <i class="bi bi-people me-2"></i>List Peminjam
                </a>
                <a href="{{ route('cekStatus.form') }}" class="btn {{ request()->routeIs('cekStatus.*') ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="bi bi-arrow-repeat me-2"></i>Pengembalian
                </a>
            </div>
        </div>
    </div>
</div> 