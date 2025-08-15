<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoUploadService
{
    /**
     * Upload foto untuk barang
     */
    public function uploadPhoto(UploadedFile $file, $barangId)
    {
        // Validasi file
        $this->validateFile($file);
        
        // Generate nama file unik
        $fileName = 'barang_' . $barangId . '_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Upload file ke storage
        $path = $file->storeAs('public/barang-photos', $fileName);
        
        return $fileName;
    }
    
    /**
     * Hapus foto dari storage
     */
    public function deletePhoto($fileName)
    {
        if ($fileName && Storage::exists('public/barang-photos/' . $fileName)) {
            Storage::delete('public/barang-photos/' . $fileName);
            return true;
        }
        return false;
    }
    
    /**
     * Validasi file upload
     */
    private function validateFile(UploadedFile $file)
    {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \Exception('File harus berupa gambar (JPG, PNG, GIF)');
        }
        
        if ($file->getSize() > $maxSize) {
            throw new \Exception('Ukuran file maksimal 2MB');
        }
    }
    
    /**
     * Get URL foto untuk ditampilkan
     */
    public function getPhotoUrl($fileName)
    {
        if (!$fileName) return null;
        return Storage::url('public/barang-photos/' . $fileName);
    }
}
