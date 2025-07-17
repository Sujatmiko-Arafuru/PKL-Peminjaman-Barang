<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPeminjaman;

class Peminjaman extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'unit', 'no_telp', 'tanggal_mulai', 'tanggal_selesai', 'keperluan', 'bukti', 'status'
    ];
    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
} 