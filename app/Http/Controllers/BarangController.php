<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function beranda(): \Illuminate\View\View
    {
        return view('beranda');
    }

    public function index(Request $request): \Illuminate\View\View
    {
        // Hapus session kode peminjaman jika user melihat dashboard (kecuali jika baru saja melakukan peminjaman)
        if (!session('success') || strpos(session('success'), 'Kode Peminjaman:') === false) {
            session()->forget('kode_peminjaman');
        }
        
        $query = Barang::query();
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        $barangs = $query->orderBy('nama')->paginate(12); // 12 item per halaman (3x4 grid)
        return view('dashboard', compact('barangs'));
    }

    public function show($id): \Illuminate\View\View
    {
        // Hapus session kode peminjaman jika user melihat detail barang
        session()->forget('kode_peminjaman');
        
        $barang = Barang::findOrFail($id);
        return view('barang_detail', compact('barang'));
    }
} 