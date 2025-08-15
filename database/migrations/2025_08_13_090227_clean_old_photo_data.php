<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bersihkan data foto lama yang masih menggunakan format JSON
        $barangs = DB::table('barangs')->get();
        
        foreach ($barangs as $barang) {
            $updates = [];
            
            // Jika foto masih berupa JSON, bersihkan
            if ($barang->foto && is_string($barang->foto) && str_starts_with($barang->foto, '[')) {
                $updates['foto'] = null;
            }
            
            // Jika ada data yang perlu diupdate
            if (!empty($updates)) {
                DB::table('barangs')->where('id', $barang->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada rollback yang diperlukan
    }
};
