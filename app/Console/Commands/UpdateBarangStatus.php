<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;

class UpdateBarangStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barang:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status semua barang berdasarkan stok tersedia';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $barangs = Barang::all();
        $updatedCount = 0;

        $this->info('Memulai update status barang...');

        foreach ($barangs as $barang) {
            $oldStatus = $barang->status;
            $stokTersedia = $barang->stok_tersedia;
            
            if ($stokTersedia <= 0) {
                $newStatus = 'tidak tersedia';
            } else {
                $newStatus = 'tersedia';
            }
            
            if ($oldStatus !== $newStatus) {
                $barang->status = $newStatus;
                $barang->save();
                $updatedCount++;
                
                $this->line("Barang '{$barang->nama}': {$oldStatus} → {$newStatus} (Stok tersedia: {$stokTersedia})");
            }
        }

        $this->info("Selesai! {$updatedCount} barang berhasil diupdate.");
        
        return Command::SUCCESS;
    }
} 