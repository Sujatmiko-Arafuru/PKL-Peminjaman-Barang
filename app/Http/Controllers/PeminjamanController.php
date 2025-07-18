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
            'unit' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'bukti' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        // Simpan data form
        $formData = $request->except('bukti');
        if ($request->hasFile('bukti')) {
            $formData['bukti'] = $request->file('bukti')->store('bukti_peminjaman', 'public');
        }
        $cart = session()->get('cart', []);
        $formData['cart'] = $cart;
        // Simpan data peminjaman tanpa kode peminjaman
        $peminjaman = \App\Models\Peminjaman::create([
            'nama' => $formData['nama'],
            'unit' => $formData['unit'],
            'no_telp' => $formData['no_telp'],
            'nama_kegiatan' => $formData['nama_kegiatan'],
            'tujuan' => $formData['tujuan'],
            'tanggal_mulai' => $formData['tanggal_mulai'],
            'tanggal_selesai' => $formData['tanggal_selesai'],
            'bukti' => $formData['bukti'],
            'status' => 'menunggu',
        ]);
        // Simpan detail barang yang dipinjam
        foreach ($formData['cart'] as $item) {
            \App\Models\DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'barang_id' => $item['id'],
                'jumlah' => $item['qty'],
            ]);
            // Kurangi stok barang
            $barang = \App\Models\Barang::find($item['id']);
            if ($barang) {
                $barang->stok = max(0, $barang->stok - $item['qty']);
                $barang->save();
            }
        }
        // Hapus session cart
        session()->forget('cart');
        return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil diajukan!');
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
        
        // Urutkan berdasarkan tanggal terbaru
        $peminjamans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('list_peminjam', compact('peminjamans'));
    }

    public function detailPeminjamPublic($id): \Illuminate\Contracts\View\View
    {
        $peminjaman = \App\Models\Peminjaman::with('details.barang')->findOrFail($id);
        return view('list_peminjam_detail', compact('peminjaman'));
    }
} 