@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/beranda.css') }}">
@endsection

@section('content')
<style>
    .hero-section {
        position: relative;
        height: 100vh;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
    }
    
    .video-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -2;
    }
    
    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: -1;
    }
    

    
    .hero-content {
        max-width: 800px;
        padding: 0 20px;
        z-index: 5;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        line-height: 1.2;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        font-weight: 400;
        margin-bottom: 2.5rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        line-height: 1.5;
    }
    
    .cta-button {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        padding: 15px 35px;
        font-size: 1.1rem;
        font-weight: 600;
        border: none;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .client-section {
        position: absolute;
        bottom: 50px;
        left: 0;
        right: 0;
        text-align: center;
        z-index: 5;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .client-text {
        font-size: 0.9rem;
        margin-bottom: 10px;
        opacity: 0.9;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 0 auto;
        text-align: center;
        display: block;
        width: fit-content;
    }
    
    /* Fallback background jika video tidak load */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        z-index: -3;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        

        
        .cta-button {
            padding: 12px 25px;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        

    }
</style>

<div class="hero-section">
    <!-- Background Video -->
    <video class="video-background" autoplay muted loop>
        <source src="{{ asset('assets/videos/background.mp4') }}" type="video/mp4">
        <!-- Fallback untuk browser yang tidak support video -->
    </video>
    
    <!-- Video Overlay -->
    <div class="video-overlay"></div>
    

    <!-- Hero Content -->
    <div class="hero-content">
        <h1 class="hero-title">Selamat Datang di SIMBARA</h1>
        <p class="hero-subtitle">
            Sistem Informasi Manajemen Barang Poltekkes Denpasar - 
            Solusi digital untuk mengelola peminjaman peralatan dan inventaris dengan mudah dan efisien.
        </p>
        <a href="{{ route('dashboard') }}" class="cta-button">
            <i class="bi bi-box-seam me-2"></i>Mulai Peminjaman
        </a>
    </div>
    
    <!-- Client Section -->
    <div class="client-section">
        <div class="client-text">Poltekkes Denpasar</div>
    </div>
</div>

<!-- Additional Content Section (Optional) -->
<div class="container-fluid py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title text-primary">Kelola Inventaris</h5>
                        <p class="card-text text-muted">Sistem yang memudahkan pengelolaan dan pemantauan inventaris barang secara real-time.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-clipboard-check text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title text-success">Proses Cepat</h5>
                        <p class="card-text text-muted">Proses peminjaman yang mudah dan cepat dengan sistem persetujuan otomatis.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-graph-up text-info" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title text-info">Laporan Detail</h5>
                        <p class="card-text text-muted">Laporan lengkap dan analisis untuk memantau penggunaan barang inventaris.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
