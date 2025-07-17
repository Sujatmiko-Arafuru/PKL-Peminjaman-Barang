<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Barang::query();
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        $barangs = $query->orderBy('nama')->get();
        return view('dashboard', compact('barangs'));
    }

    public function show($id): \Illuminate\View\View
    {
        $barang = Barang::findOrFail($id);
        return view('barang_detail', compact('barang'));
    }
} 