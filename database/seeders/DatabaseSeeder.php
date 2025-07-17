<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        Admin::create([
            'name' => 'Admin Sarpras',
            'email' => 'admin@sarpras.com',
            'password' => Hash::make('password'),
        ]);

        // Barang
        $barangs = [];
        for ($i = 1; $i <= 5; $i++) {
            $foto = [];
            for ($j = 1; $j <= rand(1, 3); $j++) {
                $filename = "dummy_barang_{$i}_{$j}.jpg";
                Storage::disk('public')->put($filename, file_get_contents('https://via.placeholder.com/300x200?text=Barang+'.($i).'+Foto+'.($j)));
                $foto[] = $filename;
            }
            $barangs[] = Barang::create([
                'nama' => 'Barang Dummy '.$i,
                'deskripsi' => 'Deskripsi barang dummy ke-'.$i,
                'stok' => rand(5, 20),
                'status' => 'tersedia',
                'foto' => json_encode($foto),
            ]);
        }

        // Peminjaman
        for ($k = 1; $k <= 3; $k++) {
            $peminjaman = Peminjaman::create([
                'nama' => 'User Dummy '.$k,
                'unit' => 'Unit '.$k,
                'no_telp' => '0812345678'.$k,
                'tanggal_mulai' => now()->subDays(rand(1, 10)),
                'tanggal_selesai' => now()->addDays(rand(1, 5)),
                'keperluan' => 'Keperluan peminjaman dummy '.$k,
                'bukti' => '',
                'status' => $k == 1 ? 'menunggu' : ($k == 2 ? 'disetujui' : 'dikembalikan'),
            ]);
            // Detail peminjaman
            $ambilBarang = $barangs[array_rand($barangs)];
            DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'barang_id' => $ambilBarang->id,
                'jumlah' => rand(1, 3),
            ]);
        }
    }
}
