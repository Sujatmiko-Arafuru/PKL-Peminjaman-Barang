<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin SarPras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/table-improvements.css') }}" rel="stylesheet">
    <style>
        body { background: #E0FFFF; }
        .sidebar {
            min-height: 100vh;
            background: #20B2AA;
            color: #fff;
            width: 220px;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
        }
        .sidebar .nav-link { color: #fff; font-weight: 500; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #008B8B; color: #fff; }
        .sidebar .nav-link .bi { margin-right: 8px; }
        .main-content { margin-left: 220px; padding: 2rem 2rem 2rem 2rem; }
        .topbar { background: #fff; border-bottom: 1px solid #E0FFFF; padding: 1rem 2rem; margin-left: 220px; }
        
        /* Button styling for admin */
        .btn {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-group .btn {
            margin: 0 2px;
        }
        
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .table thead {
            background: linear-gradient(135deg, #20B2AA, #48D1CC);
            color: white;
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
        
        @media (max-width: 991px) {
            .sidebar, .main-content, .topbar { margin-left: 0 !important; }
            .sidebar { position: static; width: 100%; min-height: auto; }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="mb-4">SarPras Admin</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="{{ route('admin.dashboard') }}" class="nav-link{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.inventaris.index') }}" class="nav-link{{ request()->routeIs('admin.inventaris.*') ? ' active' : '' }}"><i class="bi bi-box-seam"></i> Inventaris</a></li>
            <li><a href="{{ route('admin.peminjaman.index') }}" class="nav-link{{ request()->routeIs('admin.peminjaman.*') ? ' active' : '' }}"><i class="bi bi-journal-plus"></i> Peminjaman</a></li>
            <li><a href="{{ route('admin.pengembalian.index') }}" class="nav-link{{ request()->routeIs('admin.pengembalian.*') ? ' active' : '' }}"><i class="bi bi-arrow-repeat"></i> Pengembalian</a></li>
            <li><a href="{{ route('admin.arsip.index') }}" class="nav-link{{ request()->routeIs('admin.arsip.*') ? ' active' : '' }}"><i class="bi bi-archive"></i> Arsip</a></li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </li>
        </ul>
    </div>
    <div class="topbar d-flex align-items-center justify-content-between">
        <span>Selamat datang, Admin SarPras</span>
    </div>
    <main class="main-content">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 