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
        $barangs = Barang::orderBy('nama')->paginate(12); // 12 item per halaman (3x4 grid)
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
            'foto1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto3' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        // Generate kode otomatis
        $kode = 'BRG-' . strtoupper(substr(md5(uniqid()), 0, 8));
        
        // Buat barang dengan kode
        $barang = new Barang();
        $barang->kode = $kode;
        $barang->nama = $request->nama;
        $barang->deskripsi = $request->deskripsi;
        $barang->stok = $request->stok;
        $barang->status = $request->status;
        $barang->kategori = 'Umum'; // Default kategori
        $barang->satuan = 'Unit'; // Default satuan
        $barang->lokasi = 'Gudang'; // Default lokasi
        $barang->kondisi = 'Baik'; // Default kondisi
        
        // Handle foto uploads
        $this->handleFotoUploads($request, $barang);
        
        // Save barang tanpa trigger boot method
        $barang->saveQuietly();
        
        // Update status otomatis setelah save
        $barang->updateStatusOtomatis();
        
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
            'foto1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto3' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        // Update data dasar tanpa trigger boot method
        $barang->nama = $request->nama;
        $barang->deskripsi = $request->deskripsi;
        $barang->stok = $request->stok;
        $barang->status = $request->status;
        
        // Handle foto uploads
        $this->handleFotoUploads($request, $barang);
        
        // Save semua perubahan sekaligus tanpa trigger boot method
        $barang->saveQuietly();
        
        // Update status otomatis setelah save
        $barang->updateStatusOtomatis();
        
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
        
        // Hapus foto dari storage sebelum hapus barang
        $this->deleteFotoFiles($barang);
        
        $barang->delete();
        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Handle foto uploads for barang
     */
    private function handleFotoUploads(Request $request, Barang $barang): void
    {
        $fotoFields = ['foto1', 'foto2', 'foto3'];
        
        foreach ($fotoFields as $field) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '_' . $file->getClientOriginalName();
                
                // Store file in public/storage/barang-photos
                $path = $file->storeAs('barang-photos', $filename, 'public');
                
                // Set foto path
                $barang->$field = $path;
            }
            // Jika tidak ada file yang diupload, foto lama tetap dipertahankan
        }
    }

    /**
     * Delete foto files from storage
     */
    private function deleteFotoFiles(Barang $barang): void
    {
        $fotoFields = ['foto1', 'foto2', 'foto3'];
        
        foreach ($fotoFields as $field) {
            if ($barang->$field && Storage::disk('public')->exists($barang->$field)) {
                Storage::disk('public')->delete($barang->$field);
            }
        }
    }
} 