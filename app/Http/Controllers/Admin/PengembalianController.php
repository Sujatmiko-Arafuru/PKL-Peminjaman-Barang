<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        try {
            $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
            
            // Mulai transaction
            DB::beginTransaction();
            
            // Update status peminjaman
            $peminjaman->status = 'dikembalikan';
            $peminjaman->saveQuietly();
            
            // Batch update stok barang untuk optimasi
            $barangUpdates = [];
            foreach ($peminjaman->details as $detail) {
                $barang = $detail->barang;
                if ($barang) {
                    $newStok = $barang->stok + $detail->jumlah;
                    $barangUpdates[] = [
                        'id' => $barang->id,
                        'stok' => $newStok
                    ];
                }
            }
            
            // Update stok dalam batch untuk efisiensi
            foreach ($barangUpdates as $update) {
                DB::table('barangs')
                    ->where('id', $update['id'])
                    ->update(['stok' => $update['stok']]);
                    
                // Update status secara manual untuk barang yang diupdate
                $barang = Barang::find($update['id']);
                if ($barang) {
                    $barang->updateStatusOtomatis();
                }
            }
            
            DB::commit();
            return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian disetujui dan stok barang berhasil diupdate.');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat approve pengembalian: ' . $e->getMessage(), [
                'peminjaman_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.pengembalian.index')->with('error', 'Terjadi kesalahan saat approve pengembalian. Silakan coba lagi.');
        }
    }

    public function reject($id): \Illuminate\Http\RedirectResponse
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            $peminjaman->status = 'pengembalian ditolak';
            $peminjaman->saveQuietly();
            return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian berhasil ditolak.');
        } catch (\Exception $e) {
            Log::error('Error saat reject pengembalian: ' . $e->getMessage(), [
                'peminjaman_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.pengembalian.index')->with('error', 'Terjadi kesalahan saat menolak pengembalian. Silakan coba lagi.');
        }
    }
} 