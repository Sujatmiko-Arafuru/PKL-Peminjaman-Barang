<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['details.barang'])
            ->whereIn('status', ['disetujui', 'dipinjam', 'proses_pengembalian'])
            ->whereHas('details', function($query) {
                $query->whereRaw('jumlah_dikembalikan < jumlah');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pengembalian.index', compact('peminjamans'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['details.barang'])->findOrFail($id);
        
        // Pastikan peminjaman bisa dikembalikan
        if (!$peminjaman->canBeReturned()) {
            return back()->with('error', 'Peminjaman ini tidak bisa dikembalikan.');
        }
        
        return view('admin.pengembalian.show', compact('peminjaman'));
    }

    public function inputKodePengembalian(Request $request)
    {
        $request->validate([
            'kode_peminjaman' => 'nullable|string',
            'nama' => 'nullable|string',
            'nama_kegiatan' => 'nullable|string',
            'no_telp' => 'nullable|string',
        ]);

        $query = Peminjaman::with(['details.barang']);

        // Filter berdasarkan input yang diberikan
            if ($request->filled('kode_peminjaman')) {
                $query->where('kode_peminjaman', 'like', '%' . $request->kode_peminjaman . '%');
            }
            
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
            }
            
            if ($request->filled('nama_kegiatan')) {
                $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
            }
            
            if ($request->filled('no_telp')) {
                $query->where('no_telp', 'like', '%' . $request->no_telp . '%');
            }

        $peminjamans = $query->whereIn('status', ['disetujui', 'dipinjam', 'proses_pengembalian'])
            ->whereHas('details', function($subQuery) {
                $subQuery->whereRaw('jumlah_dikembalikan < jumlah');
            })->get();

        if ($peminjamans->isEmpty()) {
            return back()->with('error', 'Tidak ada data peminjaman yang ditemukan.');
        }

        if ($peminjamans->count() == 1) {
            return redirect()->route('admin.pengembalian.show', $peminjamans->first()->id);
        }

        return view('admin.pengembalian.search_result', compact('peminjamans'));
    }

    /**
     * Update detail pengembalian untuk semua detail peminjaman
     */
    public function bulkUpdatePengembalian(Request $request, $id)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.id' => 'required|exists:detail_peminjamans,id',
            'details.*.jumlah_dikembalikan' => 'required|integer|min:0',
        ]);

        $peminjaman = Peminjaman::with(['details.barang'])->findOrFail($id);
        
        // Pastikan peminjaman bisa dikembalikan
        if (!$peminjaman->canBeReturned()) {
            return back()->with('error', 'Peminjaman ini tidak bisa dikembalikan.');
        }

        DB::beginTransaction();
        try {
            $totalStokDikembalikan = 0;
            
            foreach ($request->details as $detailData) {
                $detail = DetailPeminjaman::with('barang')->find($detailData['id']);
                
                if ($detail && $detail->peminjaman_id == $peminjaman->id) {
                    $tambahan = (int) $detailData['jumlah_dikembalikan'];
                    $sisa = max(0, $detail->jumlah - $detail->jumlah_dikembalikan);
                    
                    if ($tambahan < 0) {
                        throw new \Exception("Jumlah tambahan pengembalian untuk {$detail->barang->nama} tidak boleh kurang dari 0.");
                    }
                    if ($tambahan > $sisa) {
                        throw new \Exception("Jumlah tambahan pengembalian untuk {$detail->barang->nama} tidak boleh melebihi sisa yang belum dikembalikan ({$sisa}).");
                    }
                    
                    if ($tambahan > 0) {
                        $detail->jumlah_dikembalikan += $tambahan;
                        $detail->save();
                        
                        $barang = $detail->barang;
                        $barang->stok += $tambahan;
                        $barang->save();
                        
                        $totalStokDikembalikan += $tambahan;
                    }
                }
            }

            // Update status peminjaman berdasarkan jumlah yang dikembalikan
            $this->updatePeminjamanStatus($peminjaman);
            
            DB::commit();
            
            $message = 'Pengembalian berhasil diupdate. ';
            if ($totalStokDikembalikan > 0) {
                $message .= "Stok barang telah diupdate (+{$totalStokDikembalikan}). ";
            }
            
            if ($peminjaman->status === 'proses_pengembalian') {
                $message .= 'Status berubah menjadi "Proses Pengembalian".';
            } elseif ($peminjaman->status === 'dikembalikan') {
                $message .= 'Status berubah menjadi "Dikembalikan".';
            }
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status peminjaman berdasarkan jumlah barang yang dikembalikan
     */
    private function updatePeminjamanStatus(Peminjaman $peminjaman)
    {
        // Hitung ulang dari database untuk memastikan angka terbaru
        $totalBarang = \App\Models\DetailPeminjaman::where('peminjaman_id', $peminjaman->id)
            ->sum('jumlah');
        $totalDikembalikan = \App\Models\DetailPeminjaman::where('peminjaman_id', $peminjaman->id)
            ->sum('jumlah_dikembalikan');

        if ($totalDikembalikan == 0) {
            // Jika belum ada yang dikembalikan, status tetap seperti semula
            if ($peminjaman->status === 'disetujui') {
                $peminjaman->status = 'dipinjam';
            }
        } elseif ($totalDikembalikan < $totalBarang) {
            // Jika sebagian dikembalikan, status menjadi proses pengembalian
            $peminjaman->status = 'proses_pengembalian';
        } else {
            // Jika semua dikembalikan, status menjadi dikembalikan
            $peminjaman->status = 'dikembalikan';
        }

        $peminjaman->save();
    }

    /**
     * Get peminjaman yang bisa dikembalikan untuk API
     */
    public function getPeminjamanReturnable()
    {
        $peminjamans = Peminjaman::with(['details.barang'])
            ->whereIn('status', ['disetujui', 'dipinjam', 'proses_pengembalian'])
            ->whereHas('details', function($query) {
                $query->whereRaw('jumlah_dikembalikan < jumlah');
            })
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json([
                'success' => true,
            'data' => $peminjamans
        ]);
    }
} 