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
        $jumlah = (int) $request->input('jumlah', 1);
        
        // Cek apakah barang tersedia dan stok mencukupi
        if (!$barang->bisaDipinjam($jumlah)) {
            if (!$request->expectsJson()) {
                return redirect()->back()->with('error', 'Barang "' . $barang->nama . '" tidak tersedia atau stok tidak mencukupi.');
            }
            return response()->json(['success' => false, 'message' => 'Barang tidak tersedia atau stok tidak mencukupi']);
        }
        
        $cart = session()->get('cart', []);
        
        if(isset($cart[$id])) {
            // Cek apakah penambahan jumlah tidak melebihi stok tersedia
            $totalQty = $cart[$id]['qty'] + $jumlah;
            if ($totalQty > $barang->stok_tersedia) {
                if (!$request->expectsJson()) {
                    return redirect()->back()->with('error', 'Jumlah melebihi stok tersedia untuk barang "' . $barang->nama . '".');
                }
                return response()->json(['success' => false, 'message' => 'Jumlah melebihi stok tersedia']);
            }
            $cart[$id]['qty'] = $totalQty;
        } else {
            // Ambil foto utama (pertama) dari array JSON
            $fotoArray = $barang->foto ? json_decode($barang->foto, true) : [];
            $fotoUtama = $fotoArray && count($fotoArray) > 0 ? $fotoArray[0] : null;
            $cart[$id] = [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'foto' => $fotoUtama,
                'stok' => $barang->stok,
                'stok_tersedia' => $barang->stok_tersedia,
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

    public function updateQty(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $action = $request->input('action'); // 'increase' or 'decrease'
        $cart = session()->get('cart', []);
        
        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di keranjang']);
        }
        
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan']);
        }
        
        // Cek apakah barang masih tersedia
        if (!$barang->bisaDipinjam(1)) {
            return response()->json(['success' => false, 'message' => 'Barang tidak tersedia']);
        }
        
        $currentQty = $cart[$id]['qty'];
        
        if ($action === 'increase') {
            // Check if we can increase quantity (not exceed available stock)
            $availableStock = $barang->stok_tersedia;
            if ($currentQty < $availableStock) {
                $cart[$id]['qty'] = $currentQty + 1;
            } else {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi']);
            }
        } elseif ($action === 'decrease') {
            // Check if we can decrease quantity (not below 1)
            if ($currentQty > 1) {
                $cart[$id]['qty'] = $currentQty - 1;
            } else {
                return response()->json(['success' => false, 'message' => 'Jumlah minimal adalah 1']);
            }
        }
        
        session(['cart' => $cart]);
        
        return response()->json([
            'success' => true,
            'newQty' => $cart[$id]['qty'],
            'stock' => $barang->stok_tersedia,
            'message' => 'Jumlah berhasil diperbarui'
        ]);
    }

    public function index(): \Illuminate\View\View
    {
        $cart = session()->get('cart', []);
        
        // Bersihkan cart dari barang yang sudah tidak ada atau tidak tersedia
        $cleanedCart = [];
        foreach ($cart as $itemId => $item) {
            $barang = Barang::find($item['id']);
            if ($barang && $barang->bisaDipinjam($item['qty'])) {
                // Update data barang dengan informasi terbaru
                $fotoArray = $barang->foto ? json_decode($barang->foto, true) : [];
                $fotoUtama = $fotoArray && count($fotoArray) > 0 ? $fotoArray[0] : null;
                
                $cleanedCart[$itemId] = [
                    'id' => $barang->id,
                    'nama' => $barang->nama,
                    'foto' => $fotoUtama,
                    'stok' => $barang->stok,
                    'stok_tersedia' => $barang->stok_tersedia,
                    'status' => $barang->status,
                    'qty' => $item['qty']
                ];
            }
        }
        
        // Update session cart dengan data yang sudah dibersihkan
        session(['cart' => $cleanedCart]);
        
        return view('keranjang', compact('cleanedCart'));
    }
} 