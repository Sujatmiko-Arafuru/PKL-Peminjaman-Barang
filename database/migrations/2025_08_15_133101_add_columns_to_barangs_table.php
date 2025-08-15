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
            $table->string('kode')->unique()->after('id');
            $table->string('kategori')->after('nama');
            $table->string('satuan')->default('Unit')->after('stok');
            $table->string('lokasi')->nullable()->after('satuan');
            $table->string('kondisi')->default('Baik')->after('lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['kode', 'kategori', 'satuan', 'lokasi', 'kondisi']);
        });
    }
};
