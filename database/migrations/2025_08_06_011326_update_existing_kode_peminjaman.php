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
        // Update existing kode peminjaman to new format
        $peminjamans = \App\Models\Peminjaman::all();
        
        foreach ($peminjamans as $peminjaman) {
            // Skip if already in new format (contains 4 digits at the end)
            if (preg_match('/^[A-Z]{3}-\d{8}-\d{4}$/', $peminjaman->kode_peminjaman)) {
                continue;
            }
            
            // Generate new kode peminjaman
            $namaAwal = strtoupper(substr($peminjaman->nama, 0, 3));
            $tanggalMulai = date('Ymd', strtotime($peminjaman->tanggal_mulai));
            
            // Find the last peminjaman for the same date and name prefix
            $lastPeminjaman = \App\Models\Peminjaman::where('kode_peminjaman', 'like', $namaAwal . '-' . $tanggalMulai . '-%')
                ->where('id', '!=', $peminjaman->id)
                ->orderBy('kode_peminjaman', 'desc')
                ->first();
            
            if ($lastPeminjaman) {
                // Extract number from last kode
                $lastNumber = intval(substr($lastPeminjaman->kode_peminjaman, -4));
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
            
            $newKode = $namaAwal . '-' . $tanggalMulai . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Ensure uniqueness
            while (\App\Models\Peminjaman::where('kode_peminjaman', $newKode)->where('id', '!=', $peminjaman->id)->exists()) {
                $nextNumber++;
                $newKode = $namaAwal . '-' . $tanggalMulai . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
            
            // Update the peminjaman
            $peminjaman->update(['kode_peminjaman' => $newKode]);
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
