<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PKL - Sistem Peminjaman Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .dashboard-title {
            color: #0d6efd;
            font-weight: 600;
        }
        
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
        
        .form-control:disabled {
            background-color: #e9ecef !important;
            opacity: 0.6 !important;
        }
        
        /* Sidebar menu styling */
        .card-body .btn {
            text-align: left;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .card-body .btn:hover {
            transform: translateX(5px);
        }
        
        .card-body .btn.active {
            font-weight: 600;
        }
        
        /* Navbar styling */
        .navbar .btn-outline-light {
            border-color: rgba(255,255,255,0.5);
            color: white;
            transition: all 0.3s ease;
        }
        
        .navbar .btn-outline-light:hover {
            background-color: white;
            color: #0d6efd;
            border-color: white;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-box-seam me-2"></i>Peminjaman Barang
            </a>
            <div class="navbar-nav ms-auto">
                <a class="btn btn-outline-light btn-sm" href="{{ route('admin.login') }}">
                    <i class="bi bi-person-circle me-1"></i>Login Admin
                </a>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 