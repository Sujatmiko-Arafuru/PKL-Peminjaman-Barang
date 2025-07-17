<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class KeranjangController extends Controller
{
    public function tambah(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->input('barang_id');
        $barang = Barang::findOrFail($id);
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]['qty'] += 1;
        } else {
            $cart[$id] = [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'foto' => $barang->foto,
                'stok' => $barang->stok,
                'status' => $barang->status,
                'qty' => 1
            ];
        }
        session(['cart' => $cart]);
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