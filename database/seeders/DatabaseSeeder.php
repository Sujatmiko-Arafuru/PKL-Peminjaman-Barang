<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data lama kecuali akun admin
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_peminjamans')->truncate();
        DB::table('peminjamans')->truncate();
        DB::table('barangs')->truncate();
        DB::table('users')->where('email', '!=', 'admin@sarpras.com')->delete();
        DB::table('admins')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Admin
        Admin::create([
            'name' => 'Admin Sarpras',
            'email' => 'admin@sarpras.com',
            'password' => Hash::make('password'),
        ]);
    }
}
