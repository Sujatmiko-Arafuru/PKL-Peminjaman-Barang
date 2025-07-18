<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPeminjaman;

class Peminjaman extends Model
{
    use HasFactory;
    
    protected $table = 'peminjamans';
    
    protected $fillable = [
        'nama', 'unit', 'no_telp', 'nama_kegiatan', 'tujuan', 'tanggal_mulai', 'tanggal_selesai', 'bukti', 'status', 'kode_peminjaman'
    ];
    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
} 