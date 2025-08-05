<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;

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
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
        
        // Validasi stok sebelum approve
        foreach ($peminjaman->details as $detail) {
            $barang = $detail->barang;
            $availableStock = $barang->stok_tersedia;
            
            if ($availableStock < $detail->jumlah) {
                return redirect()->route('admin.peminjaman.index')->with('error', 'Stok barang "' . $barang->nama . '" tidak mencukupi untuk approve peminjaman ini.');
            }
        }
        
        // Mulai transaction
        DB::beginTransaction();
        
        try {
            // Update status peminjaman
            $peminjaman->status = 'disetujui';
            $peminjaman->save();
            
            // Kurangi stok barang dan update status otomatis
            foreach ($peminjaman->details as $detail) {
                $barang = $detail->barang;
                $barang->stok = max(0, $barang->stok - $detail->jumlah);
                $barang->save(); // Status akan diupdate otomatis melalui boot method
            }
            
            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman disetujui dan status barang diupdate otomatis.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat approve peminjaman: ' . $e->getMessage());
        }
    }

    public function reject($id): \Illuminate\Http\RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'ditolak';
        $peminjaman->save();
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman ditolak.');
    }
} 