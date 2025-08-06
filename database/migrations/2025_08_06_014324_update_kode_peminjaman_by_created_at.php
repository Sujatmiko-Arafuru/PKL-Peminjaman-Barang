<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing kode peminjaman to new format based on created_at
        $peminjamans = \App\Models\Peminjaman::orderBy('created_at', 'asc')->get();
        
        // Group by nama prefix and tanggal
        $groups = [];
        foreach ($peminjamans as $peminjaman) {
            $namaAwal = strtoupper(substr($peminjaman->nama, 0, 3));
            $tanggalMulai = date('Ymd', strtotime($peminjaman->tanggal_mulai));
            $key = $namaAwal . '-' . $tanggalMulai;
            
            if (!isset($groups[$key])) {
                $groups[$key] = [];
            }
            $groups[$key][] = $peminjaman;
        }
        
        // Update kode peminjaman based on created_at order
        foreach ($groups as $key => $groupPeminjamans) {
            $counter = 1;
            foreach ($groupPeminjamans as $peminjaman) {
                $newKode = $key . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
                
                // Update the peminjaman
                $peminjaman->update(['kode_peminjaman' => $newKode]);
                $counter++;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed as we don't store the original kode peminjaman
    }
};
