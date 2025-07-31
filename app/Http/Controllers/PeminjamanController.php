<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function form(): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang masih kosong.');
        }
        return view('peminjaman_form', compact('cart'));
    }

    public function ajukan(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'foto_peminjam' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'unit' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'bukti' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        // Simpan data form
        $formData = $request->except(['bukti', 'foto_peminjam']);
        
        // Handle foto peminjam upload
        if ($request->hasFile('foto_peminjam')) {
            $formData['foto_peminjam'] = $request->file('foto_peminjam')->store('foto_peminjam', 'public');
        }
        
        // Handle bukti upload
        if ($request->hasFile('bukti')) {
            $formData['bukti'] = $request->file('bukti')->store('bukti_peminjaman', 'public');
        }
        $cart = session()->get('cart', []);
        
        // Filter cart untuk memastikan semua barang masih ada
        $validCart = [];
        foreach ($cart as $item) {
            $barang = \App\Models\Barang::find($item['id']);
            if ($barang && $barang->stok >= $item['qty']) {
                $validCart[] = $item;
            }
        }
        
        // Jika tidak ada barang valid di cart
        if (empty($validCart)) {
            session()->forget('cart');
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong atau semua barang tidak tersedia.');
        }
        
        $formData['cart'] = $validCart;
        // Generate kode peminjaman otomatis
        $kodePeminjaman = 'PJM-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        
        // Mulai database transaction
        DB::beginTransaction();
        
        try {
            // Simpan data peminjaman dengan kode peminjaman
            $peminjaman = \App\Models\Peminjaman::create([
            'nama' => $formData['nama'],
            'foto_peminjam' => $formData['foto_peminjam'],
            'unit' => $formData['unit'],
            'no_telp' => $formData['no_telp'],
            'nama_kegiatan' => $formData['nama_kegiatan'],
            'tanggal_mulai' => $formData['tanggal_mulai'],
            'tanggal_selesai' => $formData['tanggal_selesai'],
            'bukti' => $formData['bukti'],
            'status' => 'menunggu',
            'kode_peminjaman' => $kodePeminjaman,
        ]);
        // Simpan detail barang yang dipinjam
        foreach ($formData['cart'] as $item) {
            // Validasi barang masih ada di database
            $barang = \App\Models\Barang::find($item['id']);
            if (!$barang) {
                // Jika barang tidak ditemukan, hapus dari cart dan lanjutkan
                continue;
            }
            
            // Validasi stok masih mencukupi (gunakan stok tersedia)
            $availableStock = $barang->stok_tersedia;
            if ($availableStock < $item['qty']) {
                return redirect()->back()->withErrors(['stok' => 'Stok barang "' . $barang->nama . '" tidak mencukupi. Stok tersedia: ' . $availableStock]);
            }
            
            try {
                \App\Models\DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $item['id'],
                    'jumlah' => $item['qty'],
                ]);
                
                // JANGAN kurangi stok barang saat submit request
                // Stock hanya dikurangi saat admin approve
                        } catch (\Exception $e) {
                // Jika terjadi error, rollback dan kembalikan error
                DB::rollback();
                return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan detail peminjaman: ' . $e->getMessage()]);
            }
        }
        
        // Commit transaction jika semua berhasil
        DB::commit();
        
        // Hapus session cart
        session()->forget('cart');
        return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil diajukan!');
        
    } catch (\Exception $e) {
        // Rollback transaction jika terjadi error
        DB::rollback();
        return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat mengajukan peminjaman: ' . $e->getMessage()]);
    }
    }

    public function ajukanPengembalian(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $peminjaman = \App\Models\Peminjaman::findOrFail($id);
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Pengembalian hanya bisa diajukan jika status peminjaman disetujui.');
        }
        $peminjaman->status = 'pengembalian_diajukan';
        $peminjaman->save();
        return back()->with('success', 'Pengajuan pengembalian berhasil, menunggu persetujuan admin.');
    }

    public function cekStatusForm(): \Illuminate\Contracts\View\View
    {
        return view('cek_status_form');
    }

    public function cekStatus(Request $request): \Illuminate\Contracts\View\View
    {
        $request->validate([
            'kode_peminjaman' => 'required|string',
        ]);
        $peminjaman = \App\Models\Peminjaman::where('kode_peminjaman', $request->kode_peminjaman)
            ->first();
        return view('cek_status_hasil', compact('peminjaman'));
    }

    public function searchByKegiatan(Request $request): \Illuminate\Contracts\View\View
    {
        $request->validate([
            'nama_kegiatan' => 'required|string',
        ]);
        $peminjamans = \App\Models\Peminjaman::where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%')
            ->with('details.barang')
            ->get();
        return view('cek_status_search_result', compact('peminjamans', 'request'));
    }

    public function detailPeminjaman($id): \Illuminate\Contracts\View\View
    {
        $peminjaman = \App\Models\Peminjaman::with('details.barang')->findOrFail($id);
        return view('cek_status_detail', compact('peminjaman'));
    }

    public function listPeminjam(Request $request): \Illuminate\Contracts\View\View
    {
        $query = \App\Models\Peminjaman::with('details.barang');
        
        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan nama kegiatan jika ada
        if ($request->filled('nama_kegiatan')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
        }
        
        // Urutkan berdasarkan tanggal terbaru - tanpa pagination
        $peminjamans = $query->orderBy('created_at', 'desc')->get();
        
        return view('list_peminjam', compact('peminjamans'));
    }

    public function detailPeminjamPublic($id): \Illuminate\Contracts\View\View
    {
        $peminjaman = \App\Models\Peminjaman::with('details.barang')->findOrFail($id);
        return view('list_peminjam_detail', compact('peminjaman'));
    }
} 