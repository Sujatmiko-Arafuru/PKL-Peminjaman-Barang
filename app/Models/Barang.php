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
} 