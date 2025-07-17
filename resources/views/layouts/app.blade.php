<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background: #e3f0ff; }
        .navbar { background: #1565c0; }
        .text-primary { color: #1565c0 !important; }
        .bg-primary { background: #1565c0 !important; }
        .btn-primary { background: #1976d2; border-color: #1976d2; }
        .btn-outline-primary { color: #1976d2; border-color: #1976d2; }
        .btn-outline-primary:hover { background: #1976d2; color: #fff; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">Peminjaman Barang</a>
        <div class="d-flex align-items-center ms-auto">
            <a href="{{ route('keranjang.index') }}" class="btn btn-outline-light position-relative me-2">
                <i class="bi bi-cart3" style="font-size:1.3rem;"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="keranjang-badge">
                    {{ session('cart') ? count(session('cart')) : 0 }}
                </span>
            </a>
        </div>
    </div>
</nav>
<main>
    @yield('content')
</main>
</body>
</html> 