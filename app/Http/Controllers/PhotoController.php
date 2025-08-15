<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Services\PhotoUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PhotoController extends Controller
{
    protected $photoService;
    
    public function __construct(PhotoUploadService $photoService)
    {
        $this->photoService = $photoService;
    }
    
    /**
     * Upload foto untuk barang
     */
    public function upload(Request $request, $barangId): JsonResponse
    {
        try {
            $barang = Barang::findOrFail($barangId);
            
            // Cek apakah masih bisa upload foto
            if (!$barang->canUploadMorePhotos()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 3 foto per barang'
                ], 400);
            }
            
            // Validasi request
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            // Upload foto
            $fileName = $this->photoService->uploadPhoto($request->file('photo'), $barangId);
            
            // Tentukan kolom foto yang akan diisi
            $photoColumn = $this->getNextPhotoColumn($barang);
            
            // Update database
            $barang->update([$photoColumn => $fileName]);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload',
                'photo_url' => $this->photoService->getPhotoUrl($fileName),
                'photo_count' => $barang->fresh()->getPhotoCount()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Hapus foto barang
     */
    public function delete(Request $request, $barangId): JsonResponse
    {
        try {
            $barang = Barang::findOrFail($barangId);
            
            $request->validate([
                'photo_column' => 'required|in:foto,foto2,foto3'
            ]);
            
            $photoColumn = $request->photo_column;
            $fileName = $barang->$photoColumn;
            
            if (!$fileName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto tidak ditemukan'
                ], 404);
            }
            
            // Hapus file dari storage
            $this->photoService->deletePhoto($fileName);
            
            // Update database
            $barang->update([$photoColumn => null]);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus',
                'photo_count' => $barang->fresh()->getPhotoCount()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Tentukan kolom foto berikutnya yang kosong
     */
    private function getNextPhotoColumn(Barang $barang): string
    {
        if (!$barang->foto) return 'foto';
        if (!$barang->foto2) return 'foto2';
        if (!$barang->foto3) return 'foto3';
        
        throw new \Exception('Semua slot foto sudah terisi');
    }
}
