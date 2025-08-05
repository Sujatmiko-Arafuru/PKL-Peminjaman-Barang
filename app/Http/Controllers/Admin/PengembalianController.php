<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;

class PengembalianController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Peminjaman::query();
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_telp', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('urut')) {
            $query->orderBy('created_at', $request->urut == 'terbaru' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'asc');
        }
        $peminjamans = $query->where('status', 'pengembalian_diajukan')->paginate(10);
        return view('admin.pengembalian.index', compact('peminjamans'));
    }

    public function show($id): \Illuminate\View\View
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
        return view('admin.pengembalian.show', compact('peminjaman'));
    }

    public function approve($id): \Illuminate\Http\RedirectResponse
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
        $peminjaman->status = 'dikembalikan';
        $peminjaman->save();
        
        // Update stok barang dan status otomatis
        foreach ($peminjaman->details as $detail) {
            $barang = $detail->barang;
            if ($barang) {
                $barang->stok += $detail->jumlah;
                $barang->save(); // Status akan diupdate otomatis melalui boot method
            }
        }
        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian disetujui & stok barang diupdate otomatis.');
    }

    public function reject($id): \Illuminate\Http\RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'pengembalian ditolak';
        $peminjaman->save();
        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian ditolak.');
    }
} 