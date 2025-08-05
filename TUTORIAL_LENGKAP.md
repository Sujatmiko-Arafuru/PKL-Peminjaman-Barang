# TUTORIAL LENGKAP: Membuat Aplikasi Sistem Peminjaman Barang dengan Laravel

## Daftar Isi
1. [Pendahuluan](#pendahuluan)
2. [Persiapan Development Environment](#persiapan-development-environment)
3. [Instalasi Laravel](#instalasi-laravel)
4. [Struktur Database](#struktur-database)
5. [Membuat Model dan Migration](#membuat-model-dan-migration)
6. [Membuat Controller](#membuat-controller)
7. [Membuat View (Blade Templates)](#membuat-view-blade-templates)
8. [Implementasi Fitur Keranjang](#implementasi-fitur-keranjang)
9. [Sistem Autentikasi Admin](#sistem-autentikasi-admin)
10. [Fitur Peminjaman dan Pengembalian](#fitur-peminjaman-dan-pengembalian)
11. [Sistem Tracking Status](#sistem-tracking-status)
12. [Export PDF](#export-pdf)
13. [Testing dan Deployment](#testing-dan-deployment)

---

## 1. Pendahuluan

Aplikasi ini adalah sistem peminjaman barang yang memiliki fitur:
- **User Side**: Melihat inventaris, keranjang, ajukan peminjaman, cek status
- **Admin Side**: Kelola inventaris, approve/reject peminjaman, kelola pengembalian, arsip

### Teknologi yang Digunakan:
- **Backend**: Laravel 10
- **Frontend**: Blade Templates + Bootstrap
- **Database**: MySQL
- **PDF**: DomPDF
- **File Upload**: Laravel Storage

---

## 2. Persiapan Development Environment

### 2.1 Install Software yang Diperlukan:
```bash
# Install XAMPP/Laragon untuk Apache, MySQL, PHP
# Install Composer
# Install Node.js (untuk Vite)
```

### 2.2 Konfigurasi PHP:
Pastikan ekstensi PHP berikut sudah aktif:
- `php-mysql`
- `php-xml`
- `php-curl`
- `php-mbstring`
- `php-zip`
- `php-gd`

---

## 3. Instalasi Laravel

### 3.1 Buat Project Laravel Baru:
```bash
composer create-project laravel/laravel sistem-peminjaman
cd sistem-peminjaman
```

### 3.2 Install Dependencies:
```bash
composer require barryvdh/laravel-dompdf
npm install
npm run dev
```

### 3.3 Konfigurasi Environment:
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_peminjaman
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Struktur Database

### 4.1 Analisis Tabel yang Diperlukan:

**Tabel `barangs`:**
- `id` (Primary Key)
- `nama` (Nama barang)
- `deskripsi` (Deskripsi barang)
- `stok` (Jumlah stok)
- `status` (tersedia/tidak tersedia)
- `foto` (Path foto barang)

**Tabel `peminjamans`:**
- `id` (Primary Key)
- `nama` (Nama peminjam)
- `foto_peminjam` (Foto peminjam)
- `unit` (Unit kerja)
- `no_telp` (Nomor telepon)
- `nama_kegiatan` (Nama kegiatan)
- `tanggal_mulai` (Tanggal mulai)
- `tanggal_selesai` (Tanggal selesai)
- `bukti` (Bukti kegiatan)
- `status` (pending/disetujui/ditolak/pengembalian_diajukan/selesai)
- `kode_peminjaman` (Kode unik peminjaman)

**Tabel `detail_peminjamans`:**
- `id` (Primary Key)
- `peminjaman_id` (Foreign Key ke peminjamans)
- `barang_id` (Foreign Key ke barangs)
- `jumlah` (Jumlah yang dipinjam)

**Tabel `admins`:**
- `id` (Primary Key)
- `username` (Username admin)
- `password` (Password admin)

---

## 5. Membuat Model dan Migration

### 5.1 Buat Migration untuk Tabel Barang:
```bash
php artisan make:migration create_barangs_table
```

Isi migration:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('stok')->default(0);
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
```

### 5.2 Buat Migration untuk Tabel Peminjaman:
```bash
php artisan make:migration create_peminjamans_table
```

Isi migration:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('foto_peminjam')->nullable();
            $table->string('unit');
            $table->string('no_telp');
            $table->string('nama_kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('bukti')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'pengembalian_diajukan', 'selesai'])->default('pending');
            $table->string('kode_peminjaman')->unique();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
```

### 5.3 Buat Migration untuk Tabel Detail Peminjaman:
```bash
php artisan make:migration create_detail_peminjamans_table
```

Isi migration:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamans');
    }
};
```

### 5.4 Buat Migration untuk Tabel Admin:
```bash
php artisan make:migration create_admins_table
```

Isi migration:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
```

### 5.5 Jalankan Migration:
```bash
php artisan migrate
```

### 5.6 Buat Model:
```bash
php artisan make:model Barang
php artisan make:model Peminjaman
php artisan make:model DetailPeminjaman
php artisan make:model Admin
```

---

## 6. Membuat Controller

### 6.1 Controller untuk User Side:
```bash
php artisan make:controller BarangController
php artisan make:controller KeranjangController
php artisan make:controller PeminjamanController
```

### 6.2 Controller untuk Admin Side:
```bash
php artisan make:controller Admin/AuthController
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/InventarisController
php artisan make:controller Admin/PeminjamanController
php artisan make:controller Admin/PengembalianController
php artisan make:controller Admin/ArsipController
```

---

## 7. Implementasi Controller

### 7.1 BarangController (User Side):
```php
<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::where('status', 'tersedia')->get();
        return view('dashboard', compact('barangs'));
    }
    
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang_detail', compact('barang'));
    }
}
```

### 7.2 KeranjangController:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjang = session()->get('keranjang', []);
        $barangs = [];
        
        foreach($keranjang as $id => $jumlah) {
            $barang = Barang::find($id);
            if($barang) {
                $barangs[$id] = [
                    'barang' => $barang,
                    'jumlah' => $jumlah
                ];
            }
        }
        
        return view('keranjang', compact('barangs'));
    }
    
    public function tambah(Request $request)
    {
        $barangId = $request->barang_id;
        $jumlah = $request->jumlah;
        
        $keranjang = session()->get('keranjang', []);
        
        if(isset($keranjang[$barangId])) {
            $keranjang[$barangId] += $jumlah;
        } else {
            $keranjang[$barangId] = $jumlah;
        }
        
        session()->put('keranjang', $keranjang);
        
        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang');
    }
    
    public function hapus($id)
    {
        $keranjang = session()->get('keranjang', []);
        unset($keranjang[$id]);
        session()->put('keranjang', $keranjang);
        
        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang');
    }
    
    public function updateQty(Request $request, $id)
    {
        $keranjang = session()->get('keranjang', []);
        $keranjang[$id] = $request->jumlah;
        session()->put('keranjang', $keranjang);
        
        return redirect()->back();
    }
}
```

---

## 8. Sistem Session untuk Keranjang

### 8.1 Konfigurasi Session:
Pastikan session sudah dikonfigurasi di `config/session.php`

### 8.2 Implementasi Keranjang:
Keranjang menggunakan session Laravel untuk menyimpan data sementara:
- Key: `keranjang`
- Value: Array dengan key `barang_id` dan value `jumlah`

---

## 9. File Upload System

### 9.1 Konfigurasi Storage:
```bash
php artisan storage:link
```

### 9.2 Helper Function untuk Upload:
```php
// Di AppServiceProvider atau helper file
function uploadFile($file, $path = 'uploads')
{
    if($file) {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs($path, $filename, 'public');
        return $path . '/' . $filename;
    }
    return null;
}
```

---

## 10. Middleware untuk Admin Auth

### 10.1 Buat Middleware:
```bash
php artisan make:middleware AdminAuth
```

### 10.2 Implementasi Middleware:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if(!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        return $next($request);
    }
}
```

### 10.3 Register Middleware:
Di `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ...
    'admin.auth' => \App\Http\Middleware\AdminAuth::class,
];
```

---

## 11. View Templates (Blade)

### 11.1 Layout Utama:
```html
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Peminjaman Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Sistem Peminjaman</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('keranjang.index') }}">Keranjang</a>
                <a class="nav-link" href="{{ route('cekStatus.form') }}">Cek Status</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### 11.2 Dashboard View:
```html
@extends('layouts.app')

@section('content')
<div class="row">
    @foreach($barangs as $barang)
    <div class="col-md-4 mb-4">
        <div class="card">
            @if($barang->foto)
                <img src="{{ asset('storage/' . $barang->foto) }}" class="card-img-top" alt="{{ $barang->nama }}">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $barang->nama }}</h5>
                <p class="card-text">{{ $barang->deskripsi }}</p>
                <p class="card-text">
                    <small class="text-muted">
                        Stok: {{ $barang->stok_tersedia }} / {{ $barang->stok }}
                    </small>
                </p>
                <a href="{{ route('barang.detail', $barang->id) }}" class="btn btn-primary">Detail</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
```

---

## 12. Fitur Peminjaman

### 12.1 Form Peminjaman:
```php
// PeminjamanController
public function form()
{
    $keranjang = session()->get('keranjang', []);
    $barangs = [];
    
    foreach($keranjang as $id => $jumlah) {
        $barang = Barang::find($id);
        if($barang) {
            $barangs[$id] = [
                'barang' => $barang,
                'jumlah' => $jumlah
            ];
        }
    }
    
    return view('peminjaman_form', compact('barangs'));
}

public function ajukan(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'unit' => 'required',
        'no_telp' => 'required',
        'nama_kegiatan' => 'required',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        'foto_peminjam' => 'image|mimes:jpeg,png,jpg|max:2048',
        'bukti' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);
    
    // Upload foto peminjam
    $fotoPeminjam = null;
    if($request->hasFile('foto_peminjam')) {
        $fotoPeminjam = uploadFile($request->file('foto_peminjam'), 'peminjam');
    }
    
    // Upload bukti kegiatan
    $bukti = null;
    if($request->hasFile('bukti')) {
        $bukti = uploadFile($request->file('bukti'), 'bukti');
    }
    
    // Generate kode peminjaman
    $kodePeminjaman = 'PJM' . date('Ymd') . rand(1000, 9999);
    
    // Buat peminjaman
    $peminjaman = Peminjaman::create([
        'nama' => $request->nama,
        'foto_peminjam' => $fotoPeminjam,
        'unit' => $request->unit,
        'no_telp' => $request->no_telp,
        'nama_kegiatan' => $request->nama_kegiatan,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'bukti' => $bukti,
        'kode_peminjaman' => $kodePeminjaman
    ]);
    
    // Buat detail peminjaman
    $keranjang = session()->get('keranjang', []);
    foreach($keranjang as $barangId => $jumlah) {
        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => $barangId,
            'jumlah' => $jumlah
        ]);
    }
    
    // Clear keranjang
    session()->forget('keranjang');
    
    return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil diajukan. Kode: ' . $kodePeminjaman);
}
```

---

## 13. Admin Panel

### 13.1 AuthController untuk Admin:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        $admin = Admin::where('username', $request->username)->first();
        
        if($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard');
        }
        
        return back()->withErrors(['username' => 'Username atau password salah']);
    }
    
    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login');
    }
}
```

### 13.2 InventarisController:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('admin.inventaris.index', compact('barangs'));
    }
    
    public function create()
    {
        return view('admin.inventaris.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'stok' => 'required|integer|min:0',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $foto = null;
        if($request->hasFile('foto')) {
            $foto = uploadFile($request->file('foto'), 'barang');
        }
        
        Barang::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'foto' => $foto
        ]);
        
        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.inventaris.edit', compact('barang'));
    }
    
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'stok' => 'required|integer|min:0',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok
        ];
        
        if($request->hasFile('foto')) {
            $data['foto'] = uploadFile($request->file('foto'), 'barang');
        }
        
        $barang->update($data);
        
        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil diupdate');
    }
    
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        
        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil dihapus');
    }
}
```

---

## 14. Sistem Tracking Status

### 14.1 Cek Status Peminjaman:
```php
// PeminjamanController
public function cekStatusForm()
{
    return view('cek_status_form');
}

public function cekStatus(Request $request)
{
    $request->validate([
        'kode_peminjaman' => 'required'
    ]);
    
    $peminjaman = Peminjaman::where('kode_peminjaman', $request->kode_peminjaman)->first();
    
    if(!$peminjaman) {
        return back()->withErrors(['kode_peminjaman' => 'Kode peminjaman tidak ditemukan']);
    }
    
    return view('cek_status_hasil', compact('peminjaman'));
}

public function searchByKegiatan(Request $request)
{
    $kegiatan = $request->kegiatan;
    $peminjamans = Peminjaman::where('nama_kegiatan', 'like', '%' . $kegiatan . '%')->get();
    
    return view('cek_status_search_result', compact('peminjamans'));
}
```

---

## 15. Export PDF dengan DomPDF

### 15.1 Install DomPDF:
```bash
composer require barryvdh/laravel-dompdf
```

### 15.2 ArsipController:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use PDF;

class ArsipController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::whereIn('status', ['selesai', 'ditolak'])->get();
        return view('admin.arsip.index', compact('peminjamans'));
    }
    
    public function show($id)
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
        return view('admin.arsip.show', compact('peminjaman'));
    }
    
    public function exportPdf()
    {
        $peminjamans = Peminjaman::whereIn('status', ['selesai', 'ditolak'])->get();
        
        $pdf = PDF::loadView('admin.arsip.pdf', compact('peminjamans'));
        
        return $pdf->download('arsip-peminjaman.pdf');
    }
}
```

### 15.3 Template PDF:
```html
<!DOCTYPE html>
<html>
<head>
    <title>Arsip Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Arsip Peminjaman Barang</h1>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kegiatan</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $peminjaman)
            <tr>
                <td>{{ $peminjaman->kode_peminjaman }}</td>
                <td>{{ $peminjaman->nama }}</td>
                <td>{{ $peminjaman->nama_kegiatan }}</td>
                <td>{{ $peminjaman->tanggal_mulai }} - {{ $peminjaman->tanggal_selesai }}</td>
                <td>{{ $peminjaman->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
```

---

## 16. Testing dan Deployment

### 16.1 Testing:
```bash
# Jalankan aplikasi
php artisan serve

# Test fitur-fitur:
# 1. Tambah barang ke keranjang
# 2. Ajukan peminjaman
# 3. Login admin
# 4. Approve/reject peminjaman
# 5. Export PDF
```

### 16.2 Deployment Checklist:
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`
- [ ] Clear cache: `php artisan config:cache`
- [ ] Set permission folder storage dan bootstrap/cache
- [ ] Konfigurasi web server (Apache/Nginx)

---

## 17. Fitur Tambahan

### 17.1 Notifikasi Email:
```php
// Buat Mail class
php artisan make:mail PeminjamanNotification

// Implementasi email notification saat peminjaman diajukan/disetujui
```

### 17.2 API untuk Mobile App:
```php
// Buat API routes
Route::prefix('api')->group(function () {
    Route::get('barang', [BarangController::class, 'apiIndex']);
    Route::post('peminjaman', [PeminjamanController::class, 'apiStore']);
});
```

### 17.3 Dashboard Analytics:
```php
// Tambahkan chart untuk melihat statistik peminjaman
// Gunakan Chart.js atau library chart lainnya
```

---

## 18. Troubleshooting

### 18.1 Masalah Umum:
1. **Session tidak tersimpan**: Cek konfigurasi session di `config/session.php`
2. **File upload error**: Pastikan folder storage sudah di-link dan permission benar
3. **Database connection error**: Cek konfigurasi database di `.env`
4. **PDF tidak generate**: Pastikan DomPDF sudah terinstall dengan benar

### 18.2 Debug Tips:
- Gunakan `dd()` untuk debug data
- Cek log Laravel di `storage/logs/laravel.log`
- Gunakan `php artisan tinker` untuk testing model

---

## 19. Kesimpulan

Aplikasi sistem peminjaman barang ini memiliki fitur lengkap untuk:
- **User**: Melihat inventaris, keranjang, ajukan peminjaman, tracking status
- **Admin**: Kelola inventaris, approve/reject peminjaman, arsip, export PDF

### Keunggulan Sistem:
1. **User-friendly**: Interface yang mudah digunakan
2. **Secure**: Autentikasi admin yang aman
3. **Scalable**: Mudah dikembangkan untuk fitur baru
4. **Maintainable**: Kode yang terstruktur dan mudah dipelihara

### Langkah Selanjutnya:
1. Implementasi notifikasi email
2. Buat API untuk mobile app
3. Tambahkan dashboard analytics
4. Implementasi multi-user system
5. Tambahkan fitur reporting yang lebih advanced

---

**Catatan**: Tutorial ini memberikan panduan lengkap untuk membuat aplikasi sistem peminjaman barang dari awal hingga deployment. Setiap langkah dijelaskan secara detail dengan contoh kode yang dapat langsung diimplementasikan. 