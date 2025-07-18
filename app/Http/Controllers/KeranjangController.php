<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class KeranjangController extends Controller
{
    public function tambah(Request $request)
    {
        $id = $request->input('barang_id');
        $barang = Barang::findOrFail($id);
        $cart = session()->get('cart', []);
        $jumlah = (int) $request->input('jumlah', 1);
        if(isset($cart[$id])) {
            $cart[$id]['qty'] += $jumlah;
        } else {
            // Ambil foto utama (pertama) dari array JSON
            $fotoArray = $barang->foto ? json_decode($barang->foto, true) : [];
            $fotoUtama = $fotoArray && count($fotoArray) > 0 ? $fotoArray[0] : null;
            $cart[$id] = [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'foto' => $fotoUtama,
                'stok' => $barang->stok,
                'status' => $barang->status,
                'qty' => $jumlah
            ];
        }
        session(['cart' => $cart]);
        // Jika request dari form biasa, redirect ke keranjang dengan flash message
        if (!$request->expectsJson()) {
            return redirect()->route('keranjang.index')->with('success', 'Barang "' . $barang->nama . '" (' . $jumlah . ') berhasil ditambahkan ke keranjang!');
        }
        // Jika request AJAX/JSON
        return response()->json(['success' => true, 'cart_count' => count($cart)]);
    }

    public function hapus($id): \Illuminate\Http\RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect()->route('keranjang.index');
    }

    public function index(): \Illuminate\View\View
    {
        $cart = session()->get('cart', []);
        return view('keranjang', compact('cart'));
    }
} 