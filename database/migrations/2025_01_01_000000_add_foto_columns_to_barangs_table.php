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
            $table->string('foto1')->nullable()->after('deskripsi');
            $table->string('foto2')->nullable()->after('foto1');
            $table->string('foto3')->nullable()->after('foto2');
            // Hapus kolom foto lama jika ada
            if (Schema::hasColumn('barangs', 'foto')) {
                $table->dropColumn('foto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['foto1', 'foto2', 'foto3']);
            // Kembalikan kolom foto lama jika diperlukan
            $table->string('foto')->nullable()->after('deskripsi');
        });
    }
};
