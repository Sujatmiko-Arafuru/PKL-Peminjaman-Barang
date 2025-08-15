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
            'photo1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $barang = Barang::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'status' => $request->status,
        ]);
        
        // Upload foto jika ada
        $photoService = app(\App\Services\PhotoUploadService::class);
        
        if ($request->hasFile('photo1')) {
            $fileName = $photoService->uploadPhoto($request->file('photo1'), $barang->id);
            $barang->update(['foto' => $fileName]);
        }
        
        if ($request->hasFile('photo2')) {
            $fileName = $photoService->uploadPhoto($request->file('photo2'), $barang->id);
            $barang->update(['foto2' => $fileName]);
        }
        
        if ($request->hasFile('photo3')) {
            $fileName = $photoService->uploadPhoto($request->file('photo3'), $barang->id);
            $barang->update(['foto3' => $fileName]);
        }
        
        // Status akan diupdate otomatis melalui boot method di model Barang
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
            'photo1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $barang->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'status' => $request->status,
        ]);
        
        // Upload foto jika ada
        $photoService = app(\App\Services\PhotoUploadService::class);
        
        // Debug: Log file upload attempts
        \Log::info('Photo upload debug:', [
            'has_photo1' => $request->hasFile('photo1'),
            'has_photo2' => $request->hasFile('photo2'),
            'has_photo3' => $request->hasFile('photo3'),
            'current_foto' => $barang->foto,
            'current_foto2' => $barang->foto2,
            'current_foto3' => $barang->foto3,
        ]);
        
        if ($request->hasFile('photo1')) {
            try {
                // Delete old photo if exists
                if ($barang->foto) {
                    $photoService->deletePhoto($barang->foto);
                }
                $fileName = $photoService->uploadPhoto($request->file('photo1'), $barang->id);
                $barang->update(['foto' => $fileName]);
                \Log::info('Photo1 uploaded successfully: ' . $fileName);
            } catch (\Exception $e) {
                \Log::error('Photo1 upload failed: ' . $e->getMessage());
            }
        }
        
        if ($request->hasFile('photo2')) {
            try {
                // Delete old photo if exists
                if ($barang->foto2) {
                    $photoService->deletePhoto($barang->foto2);
                }
                $fileName = $photoService->uploadPhoto($request->file('photo2'), $barang->id);
                $barang->update(['foto2' => $fileName]);
                \Log::info('Photo2 uploaded successfully: ' . $fileName);
            } catch (\Exception $e) {
                \Log::error('Photo2 upload failed: ' . $e->getMessage());
            }
        }
        
        if ($request->hasFile('photo3')) {
            try {
                // Delete old photo if exists
                if ($barang->foto3) {
                    $photoService->deletePhoto($barang->foto3);
                }
                $fileName = $photoService->uploadPhoto($request->file('photo3'), $barang->id);
                $barang->update(['foto3' => $fileName]);
                \Log::info('Photo3 uploaded successfully: ' . $fileName);
            } catch (\Exception $e) {
                \Log::error('Photo3 upload failed: ' . $e->getMessage());
            }
        }
        
        // Status akan diupdate otomatis melalui boot method di model Barang
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