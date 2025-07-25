<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;
    
    protected $table = 'detail_peminjamans';
    
    protected $fillable = [
        'peminjaman_id', 'barang_id', 'jumlah'
    ];
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
} 