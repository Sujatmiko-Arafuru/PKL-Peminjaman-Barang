<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMBARA - Poltekkes Denpasar</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" onerror="this.href='{{ asset('favicon.ico') }}'">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/table-improvements.css') }}" rel="stylesheet">
    @yield('head')
    <style>
        .dashboard-title {
            color: #20B2AA;
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
            color: #20B2AA;
            border-color: white;
        }
        
        /* Logo styling in navbar */
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.3rem;
        }
        
        .navbar-logo {
            height: 40px;
            width: auto;
            margin-right: 12px;
            object-fit: contain;
            /* Show original logo colors with white background */
            background-color: rgba(255, 255, 255, 0.95);
            padding: 4px 8px;
            border-radius: 6px;
            /* Debug: make sure image is visible */
            display: inline-block !important;
            vertical-align: middle;
            /* Ensure image loads */
            max-width: 120px;
        }
        
        /* Responsive navbar logo */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            .navbar-logo {
                height: 32px;
                margin-right: 8px;
                padding: 3px 6px;
            }
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('beranda') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SIMBARA" class="navbar-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                <i class="bi bi-hospital me-2" style="display: none; color: white;"></i>
                SIMBARA Poltekkes Denpasar
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
    
    <script>
        // Debug script untuk memastikan logo loading
        document.addEventListener('DOMContentLoaded', function() {
            const logoImg = document.querySelector('.navbar-logo');
            if (logoImg) {
                logoImg.onload = function() {
                    console.log('Logo loaded successfully:', this.src);
                };
                logoImg.onerror = function() {
                    console.error('Logo failed to load:', this.src);
                    this.style.display = 'none';
                    const fallbackIcon = this.nextElementSibling;
                    if (fallbackIcon) {
                        fallbackIcon.style.display = 'inline-block';
                    }
                };
                
                // Force reload if already loaded
                if (logoImg.complete && logoImg.naturalHeight === 0) {
                    logoImg.onerror();
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html> 