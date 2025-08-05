<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs'; // Nama tabel, sesuaikan dengan migration nanti
    protected $fillable = [
        'nama', 'deskripsi', 'stok', 'status', 'foto'
    ];
    
    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
    
    public function getStokDipinjamAttribute()
    {
        return $this->details()
            ->join('peminjamans', 'detail_peminjamans.peminjaman_id', '=', 'peminjamans.id')
            ->whereIn('peminjamans.status', ['disetujui', 'pengembalian_diajukan'])
            ->sum('detail_peminjamans.jumlah');
    }
    
    public function getStokTersediaAttribute()
    {
        // Stok tersedia = total stok - stok yang sedang dipinjam
        $stokDipinjam = $this->stok_dipinjam;
        $stokTersedia = max(0, $this->stok - $stokDipinjam);
        return $stokTersedia;
    }
    
    // Method untuk reset stok jika diperlukan
    public function resetStok($newStok)
    {
        $this->stok = $newStok;
        $this->save();
    }
    
    // Method untuk mengupdate status otomatis berdasarkan stok tersedia
    public function updateStatusOtomatis()
    {
        $stokTersedia = $this->stok_tersedia;
        
        if ($stokTersedia <= 0) {
            $this->status = 'tidak tersedia';
        } else {
            $this->status = 'tersedia';
        }
        
        $this->save();
    }
    
    // Method untuk mengecek apakah barang bisa dipinjam
    public function bisaDipinjam($jumlah = 1)
    {
        return $this->status === 'tersedia' && $this->stok_tersedia >= $jumlah;
    }
    
    // Override save method untuk mengupdate status otomatis
    protected static function boot()
    {
        parent::boot();
        
        // Setelah model disimpan, update status otomatis
        static::saved(function ($barang) {
            $barang->updateStatusOtomatis();
        });
    }
} 