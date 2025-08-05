<div class="col-md-3 col-lg-2">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3"><i class="bi bi-list"></i> Menu</h5>
            <div class="d-grid gap-2">
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