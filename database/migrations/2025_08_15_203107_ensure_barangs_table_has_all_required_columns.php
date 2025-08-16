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
        Schema::table('barangs', function (Blueprint $table) {
            // Add kode column if not exists
            if (!Schema::hasColumn('barangs', 'kode')) {
                $table->string('kode')->unique()->after('id');
            }
            
            // Add kategori column if not exists
            if (!Schema::hasColumn('barangs', 'kategori')) {
                $table->string('kategori')->default('Umum')->after('nama');
            }
            
            // Add satuan column if not exists
            if (!Schema::hasColumn('barangs', 'satuan')) {
                $table->string('satuan')->default('Unit')->after('stok');
            }
            
            // Add lokasi column if not exists
            if (!Schema::hasColumn('barangs', 'lokasi')) {
                $table->string('lokasi')->default('Gudang')->after('satuan');
            }
            
            // Add kondisi column if not exists
            if (!Schema::hasColumn('barangs', 'kondisi')) {
                $table->string('kondisi')->default('Baik')->after('lokasi');
            }
            
            // Add foto2 and foto3 columns if not exist
            if (!Schema::hasColumn('barangs', 'foto2')) {
                $table->string('foto2')->nullable()->after('foto');
            }
            
            if (!Schema::hasColumn('barangs', 'foto3')) {
                $table->string('foto3')->nullable()->after('foto2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['kode', 'kategori', 'satuan', 'lokasi', 'kondisi', 'foto2', 'foto3']);
        });
    }
};
