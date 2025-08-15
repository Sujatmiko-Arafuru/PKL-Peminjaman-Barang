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
        $peminjaman = null;
        
        // Jika ada request POST (form submission), lakukan pencarian
        if ($request->isMethod('post') || $request->hasAny(['kode_peminjaman', 'nama_peminjam', 'nama_kegiatan', 'no_telp'])) {
            $query = Peminjaman::query();
            
            // Filter berdasarkan status yang bisa dikembalikan
            $query->whereIn('status', ['disetujui', 'pengembalian_diajukan']);
            
            // Pencarian fleksibel - admin bisa mengisi salah satu atau lebih
            if ($request->filled('kode_peminjaman')) {
                $query->where('kode_peminjaman', 'like', '%' . $request->kode_peminjaman . '%');
            }
            
            if ($request->filled('nama_peminjam')) {
                $query->where('nama', 'like', '%' . $request->nama_peminjam . '%');
            }
            
            if ($request->filled('nama_kegiatan')) {
                $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
            }
            
            if ($request->filled('no_telp')) {
                $query->where('no_telp', 'like', '%' . $request->no_telp . '%');
            }
            
            // Ambil data pertama yang cocok
            $peminjaman = $query->first();
            
            // Jika tidak ada data yang cocok, set error message
            if (!$peminjaman) {
                session()->flash('error', 'Data peminjaman tidak ditemukan. Silakan cek kembali data yang diinput.');
            }
        }
        
        return view('admin.pengembalian.index', compact('peminjaman'));
    }

    public function show($id): \Illuminate\View\View
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
        return view('admin.pengembalian.show', compact('peminjaman'));
    }

    public function inputKodePengembalian(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'kode_peminjaman' => 'nullable|string|max:50',
            'nama_peminjam' => 'nullable|string|max:100',
            'nama_kegiatan' => 'nullable|string|max:200',
            'no_telp' => 'nullable|string|max:20'
        ]);

        try {
            // Validasi minimal harus ada satu field yang diisi
            if (!$request->filled('kode_peminjaman') && 
                !$request->filled('nama_peminjam') && 
                !$request->filled('nama_kegiatan') && 
                !$request->filled('no_telp')) {
                return redirect()->route('admin.pengembalian.index')
                               ->with('error', 'Minimal harus mengisi salah satu field untuk pencarian.');
            }

            $query = Peminjaman::query();
            
            // Filter berdasarkan status yang bisa dikembalikan
            $query->whereIn('status', ['disetujui', 'pengembalian_diajukan']);
            
            // Pencarian berdasarkan field yang diisi
            if ($request->filled('kode_peminjaman')) {
                $query->where('kode_peminjaman', 'like', '%' . $request->kode_peminjaman . '%');
            }
            
            if ($request->filled('nama_peminjam')) {
                $query->where('nama', 'like', '%' . $request->nama_peminjam . '%');
            }
            
            if ($request->filled('nama_kegiatan')) {
                $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
            }
            
            if ($request->filled('no_telp')) {
                $query->where('no_telp', 'like', '%' . $request->no_telp . '%');
            }

            // Cari peminjaman yang sesuai
            $peminjaman = $query->first();

            if (!$peminjaman) {
                return redirect()->route('admin.pengembalian.index')
                               ->with('error', 'Data peminjaman tidak ditemukan atau status tidak sesuai.');
            }

            // Update status menjadi pengembalian_diajukan jika masih disetujui
            if ($peminjaman->status == 'disetujui') {
                $peminjaman->status = 'pengembalian_diajukan';
                $peminjaman->saveQuietly();
                
                return redirect()->route('admin.pengembalian.index')
                               ->with('success', 'Kode pengembalian berhasil diinput. Status: Pengembalian Diajukan');
            } else {
                return redirect()->route('admin.pengembalian.index')
                               ->with('info', 'Data peminjaman ditemukan dengan status: ' . ucfirst($peminjaman->status));
            }

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