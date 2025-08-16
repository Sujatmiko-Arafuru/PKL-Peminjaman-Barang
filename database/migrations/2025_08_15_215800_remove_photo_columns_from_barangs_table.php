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
            // Hapus kolom foto jika ada
            if (Schema::hasColumn('barangs', 'foto')) {
                $table->dropColumn('foto');
            }
            if (Schema::hasColumn('barangs', 'foto2')) {
                $table->dropColumn('foto2');
            }
            if (Schema::hasColumn('barangs', 'foto3')) {
                $table->dropColumn('foto3');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Tambahkan kembali kolom foto jika rollback
            $table->string('foto')->nullable();
            $table->string('foto2')->nullable();
            $table->string('foto3')->nullable();
        });
    }
};
