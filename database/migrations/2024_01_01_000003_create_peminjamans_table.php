<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->string('no_telp');
            $table->string('nama_kegiatan');
            $table->string('tujuan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('bukti');
            $table->string('status')->default('menunggu');
            $table->string('kode_peminjaman')->unique();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
}; 