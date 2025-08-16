<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'nama',
        'deskripsi',
        'foto1',
        'foto2',
        'foto3',
        'stok',
        'status',
        'kode',
        'kategori',
        'satuan',
        'lokasi',
        'kondisi'
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Hanya update status otomatis jika stok berubah atau baru dibuat
        static::saved(function ($barang) {
            if ($barang->wasChanged('stok') || $barang->wasRecentlyCreated) {
                $barang->updateStatusOtomatis();
            }
        });
    }

    public function peminjamanDetails(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function getStokDipinjamAttribute()
    {
        return $this->peminjamanDetails()
            ->whereHas('peminjaman', function ($query) {
                $query->whereIn('status', ['disetujui', 'dipinjam']);
            })
            ->sum('jumlah');
    }

    public function getStokTersediaAttribute()
    {
        return $this->stok - $this->stok_dipinjam;
    }

    public function updateStatusOtomatis()
    {
        $stokTersedia = $this->stok_tersedia;
        
        if ($stokTersedia <= 0) {
            $this->status = 'tidak tersedia';
        } else {
            $this->status = 'tersedia';
        }
        
        // Gunakan saveQuietly untuk mencegah infinite loop
        if ($this->isDirty('status')) {
            $this->saveQuietly();
        }
    }

    public function resetStok()
    {
        $this->stok = $this->stok + $this->stok_dipinjam;
        $this->saveQuietly();
        $this->updateStatusOtomatis();
    }

    public function bisaDipinjam()
    {
        return $this->status === 'tersedia' && $this->stok_tersedia > 0;
    }

    /**
     * Get all photos for this item
     */
    public function getPhotosAttribute()
    {
        $photos = [];
        if ($this->foto1) $photos[] = $this->foto1;
        if ($this->foto2) $photos[] = $this->foto2;
        if ($this->foto3) $photos[] = $this->foto3;
        return $photos;
    }

    /**
     * Get the main photo (first available photo)
     */
    public function getMainPhotoAttribute()
    {
        return $this->foto1 ?: asset('assets/images/placeholder-image.svg');
    }

    /**
     * Check if item has photos
     */
    public function hasPhotos()
    {
        return !empty($this->foto1) || !empty($this->foto2) || !empty($this->foto3);
    }

    /**
     * Get photo count
     */
    public function getPhotoCountAttribute()
    {
        return count($this->photos);
    }
} 