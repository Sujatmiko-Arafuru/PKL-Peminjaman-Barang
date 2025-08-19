<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;

class ClearPeminjamanData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:clear-peminjaman {--force : Force delete without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus semua data peminjaman dan arsip';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  PERINGATAN: Command ini akan menghapus SEMUA data peminjaman dan arsip!');
            $this->warn('Data yang akan dihapus:');
            $this->warn('- Semua peminjaman');
            $this->warn('- Semua detail peminjaman');
            $this->warn('- Semua arsip');
            $this->warn('');
            
            if (!$this->confirm('Apakah Anda yakin ingin melanjutkan? Data TIDAK BISA DIPULIHKAN!')) {
                $this->info('Operasi dibatalkan.');
                return 0;
            }
            
            if (!$this->confirm('Konfirmasi kedua: Apakah Anda BENAR-BENAR yakin? Ketik "ya" untuk melanjutkan')) {
                $this->info('Operasi dibatalkan.');
                return 0;
            }
        }

        $this->info('🚀 Memulai penghapusan data...');

        try {
            // Hitung jumlah data yang akan dihapus
            $totalPeminjaman = Peminjaman::count();
            $totalDetail = DetailPeminjaman::count();

            $this->info("📊 Data yang akan dihapus:");
            $this->info("- Peminjaman: {$totalPeminjaman} records");
            $this->info("- Detail Peminjaman: {$totalDetail} records");

            if ($totalPeminjaman == 0 && $totalDetail == 0) {
                $this->info('✅ Tidak ada data yang perlu dihapus.');
                return 0;
            }

            // Hapus detail peminjaman terlebih dahulu (foreign key constraint)
            $this->info('🗑️  Menghapus detail peminjaman...');
            DetailPeminjaman::query()->delete();
            $this->info('✅ Detail peminjaman berhasil dihapus.');

            // Hapus peminjaman
            $this->info('🗑️  Menghapus data peminjaman...');
            Peminjaman::query()->delete();
            $this->info('✅ Data peminjaman berhasil dihapus.');

            // Reset auto increment
            $this->info('🔄 Reset auto increment...');
            DB::statement('ALTER TABLE peminjamans AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE detail_peminjamans AUTO_INCREMENT = 1');
            $this->info('✅ Auto increment berhasil direset.');

            $this->info('');
            $this->info('🎉 SELESAI! Semua data peminjaman dan arsip berhasil dihapus.');
            $this->info('');
            $this->info('📋 Ringkasan:');
            $this->info("- {$totalPeminjaman} peminjaman dihapus");
            $this->info("- {$totalDetail} detail peminjaman dihapus");
            $this->info('- Auto increment direset ke 1');
            $this->info('');
            $this->info('💡 Tips: Gunakan --force untuk skip konfirmasi');

        } catch (\Exception $e) {
            $this->error('❌ Terjadi kesalahan saat menghapus data:');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
