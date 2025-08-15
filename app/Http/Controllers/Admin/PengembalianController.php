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
        
        // Filter berdasarkan status pengembalian
        $query->whereIn('status', ['pengembalian_diajukan', 'disetujui']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_telp', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_peminjaman', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('urut')) {
            $query->orderBy('created_at', $request->urut == 'terbaru' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $peminjamans = $query->paginate(10);
        return view('admin.pengembalian.index', compact('peminjamans'));
    }

    public function show($id): \Illuminate\View\View
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
        return view('admin.pengembalian.show', compact('peminjaman'));
    }

    public function inputKodePengembalian(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'kode_peminjaman' => 'required|string|max:50',
            'nama_peminjam' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20'
        ]);

        try {
            // Cari peminjaman berdasarkan kode
            $peminjaman = Peminjaman::where('kode_peminjaman', $request->kode_peminjaman)
                                   ->where('nama', $request->nama_peminjam)
                                   ->where('no_telp', $request->no_telp)
                                   ->where('status', 'disetujui')
                                   ->first();

            if (!$peminjaman) {
                return redirect()->route('admin.pengembalian.index')
                               ->with('error', 'Data peminjaman tidak ditemukan atau status tidak sesuai.');
            }

            // Update status menjadi pengembalian_diajukan
            $peminjaman->status = 'pengembalian_diajukan';
            $peminjaman->saveQuietly();

            return redirect()->route('admin.pengembalian.index')
                           ->with('success', 'Kode pengembalian berhasil diinput. Status: Pengembalian Diajukan');

        } catch (\Exception $e) {
            Log::error('Error saat input kode pengembalian: ' . $e->getMessage());
            return redirect()->route('admin.pengembalian.index')
                           ->with('error', 'Terjadi kesalahan saat input kode pengembalian.');
        }
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

    public function updateDetailPengembalian(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'detail_id' => 'required|integer|exists:detail_peminjamans,id',
                'jumlah_dikembalikan' => 'required|integer|min:1'
            ]);

            $detail = DetailPeminjaman::findOrFail($request->detail_id);
            $peminjaman = Peminjaman::findOrFail($id);

            // Validasi jumlah yang dikembalikan tidak melebihi yang dipinjam
            if ($request->jumlah_dikembalikan > $detail->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah yang dikembalikan tidak boleh melebihi yang dipinjam'
                ], 400);
            }

            // Update jumlah yang dikembalikan
            $detail->jumlah_dikembalikan = $request->jumlah_dikembalikan;
            $detail->save();

            // Update stok barang
            $barang = $detail->barang;
            if ($barang) {
                $barang->stok += $request->jumlah_dikembalikan;
                $barang->save();
                $barang->updateStatusOtomatis();
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail pengembalian berhasil diupdate',
                'data' => [
                    'jumlah_dikembalikan' => $detail->jumlah_dikembalikan,
                    'stok_barang' => $barang ? $barang->stok : null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error saat update detail pengembalian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat update detail pengembalian'
            ], 500);
        }
    }
} 