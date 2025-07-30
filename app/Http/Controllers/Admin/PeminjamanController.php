<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;

class PeminjamanController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Peminjaman::query();
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_telp', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_kegiatan', 'like', '%' . $request->search . '%');
        }
        
        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }
        
        // Sorting
        if ($request->filled('urut')) {
            $query->orderBy('created_at', $request->urut == 'terbaru' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Tanpa pagination - tampilkan semua data
        $peminjamans = $query->get();
        
        // Data untuk tabel terpisah (tanpa pagination)
        $menunggu = Peminjaman::where('status', 'menunggu')->orderBy('created_at', 'desc')->get();
        $sedang_berlangsung = Peminjaman::where('status', 'disetujui')->orderBy('created_at', 'desc')->get();
        
        return view('admin.peminjaman.index', compact('peminjamans', 'menunggu', 'sedang_berlangsung'));
    }

    public function show($id): \Illuminate\View\View
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function approve($id): \Illuminate\Http\RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'disetujui';
        $peminjaman->save();
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman disetujui.');
    }

    public function reject($id): \Illuminate\Http\RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'ditolak';
        $peminjaman->save();
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman ditolak.');
    }
} 