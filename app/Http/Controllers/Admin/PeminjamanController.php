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
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_telp', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('urut')) {
            $query->orderBy('created_at', $request->urut == 'terbaru' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'asc');
        }
        $peminjamans = $query->where('status', 'menunggu')->paginate(10);
        return view('admin.peminjaman.index', compact('peminjamans'));
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