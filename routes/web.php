<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\ArsipController;

Route::get('/', [BarangController::class, 'index'])->name('dashboard');
Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.detail');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::get('/peminjaman/form', [PeminjamanController::class, 'form'])->name('peminjaman.form');
Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan');
Route::post('/pengembalian/ajukan/{id}', [PeminjamanController::class, 'ajukanPengembalian'])->name('pengembalian.ajukan');
Route::get('/cek-status', [PeminjamanController::class, 'cekStatusForm'])->name('cekStatus.form');
Route::post('/cek-status', [PeminjamanController::class, 'cekStatus'])->name('cekStatus.submit');
Route::get('/cek-status/search', [PeminjamanController::class, 'searchByKegiatan'])->name('cekStatus.search');
Route::get('/cek-status/detail/{id}', [PeminjamanController::class, 'detailPeminjaman'])->name('cekStatus.detail');
Route::get('/list-peminjam', [PeminjamanController::class, 'listPeminjam'])->name('list.peminjam');
Route::get('/list-peminjam/detail/{id}', [PeminjamanController::class, 'detailPeminjamPublic'])->name('list.peminjam.detail');

// Route login admin
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Route group admin dengan middleware
Route::middleware([\App\Http\Middleware\AdminAuth::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    // CRUD Inventaris
    Route::resource('inventaris', InventarisController::class);
    // Kelola Peminjaman
    Route::get('peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{id}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    // Kelola Pengembalian
    Route::get('pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('pengembalian/{id}', [PengembalianController::class, 'show'])->name('pengembalian.show');
    Route::post('pengembalian/{id}/approve', [PengembalianController::class, 'approve'])->name('pengembalian.approve');
    Route::post('pengembalian/{id}/reject', [PengembalianController::class, 'reject'])->name('pengembalian.reject');
    // Arsip
    Route::get('arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('arsip/{id}', [ArsipController::class, 'show'])->name('arsip.show');
    Route::get('arsip/export/pdf', [ArsipController::class, 'exportPdf'])->name('arsip.export.pdf');
});
