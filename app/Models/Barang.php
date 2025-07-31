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
        return max(0, $this->stok - $this->stok_dipinjam);
    }
    
    // Method untuk reset stok jika diperlukan
    public function resetStok($newStok)
    {
        $this->stok = $newStok;
        $this->save();
    }
} 