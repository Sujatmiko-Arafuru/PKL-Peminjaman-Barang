<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;
    
    protected $table = 'detail_peminjamans';
    
    protected $fillable = [
        'peminjaman_id', 'barang_id', 'jumlah', 'jumlah_dikembalikan'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'jumlah_dikembalikan' => 'integer',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get jumlah yang belum dikembalikan
     */
    public function getSisaBelumDikembalikanAttribute()
    {
        return $this->jumlah - $this->jumlah_dikembalikan;
    }

    /**
     * Check if all items are returned
     */
    public function isAllReturned()
    {
        return $this->jumlah_dikembalikan >= $this->jumlah;
    }

    /**
     * Check if partially returned
     */
    public function isPartiallyReturned()
    {
        return $this->jumlah_dikembalikan > 0 && $this->jumlah_dikembalikan < $this->jumlah;
    }
} 