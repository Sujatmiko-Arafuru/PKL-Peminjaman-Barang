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
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('keperluan');
            $table->string('bukti');
            $table->string('status')->default('menunggu');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
}; 