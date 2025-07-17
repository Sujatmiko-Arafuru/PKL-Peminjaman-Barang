<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class InventarisController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $barangs = Barang::orderBy('nama')->get();
        return view('admin.inventaris.index', compact('barangs'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('admin.inventaris.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,tidak tersedia',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $fotoPaths[] = $foto->store('barang_foto', 'public');
            }
        }
        $barang = Barang::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'status' => $request->status,
            'foto' => json_encode($fotoPaths),
        ]);
        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id): \Illuminate\View\View
    {
        $barang = Barang::findOrFail($id);
        return view('admin.inventaris.edit', compact('barang'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $barang = Barang::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,tidak tersedia',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $fotoPaths = $barang->foto ? json_decode($barang->foto, true) : [];
        // Hapus foto lama yang tidak di-keep
        $keep = $request->input('keep_foto', []);
        $fotoPaths = array_values(array_intersect($fotoPaths, $keep));
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $fotoPaths[] = $foto->store('barang_foto', 'public');
            }
        }
        $barang->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'status' => $request->status,
            'foto' => json_encode($fotoPaths),
        ]);
        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil diupdate.');
    }

    public function show($id): \Illuminate\View\View
    {
        $barang = Barang::findOrFail($id);
        return view('admin.inventaris.show', compact('barang'));
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $barang = Barang::findOrFail($id);
        // Cek apakah barang sedang dipinjam
        $sedangDipinjam = $barang->stok < $barang->getOriginal('stok');
        if ($sedangDipinjam) {
            return back()->with('error', 'Barang tidak bisa dihapus karena sedang dipinjam.');
        }
        $barang->delete();
        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil dihapus.');
    }
} 