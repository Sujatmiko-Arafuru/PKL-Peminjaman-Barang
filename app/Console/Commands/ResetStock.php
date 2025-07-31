<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;

class ResetStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:reset {--item=} {--stok=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset stock for items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $itemName = $this->option('item');
        $newStok = $this->option('stok');
        
        if ($itemName && $newStok) {
            // Reset specific item
            $barang = Barang::where('nama', 'like', '%' . $itemName . '%')->first();
            if ($barang) {
                $barang->resetStok($newStok);
                $this->info("Stock for '{$barang->nama}' reset to {$newStok}");
            } else {
                $this->error("Item '{$itemName}' not found");
            }
        } else {
            // Show current stock status
            $this->info("Current Stock Status:");
            $this->info("===================");
            
            Barang::all()->each(function($barang) {
                $this->line("{$barang->nama}:");
                $this->line("  - Total Stock: {$barang->stok}");
                $this->line("  - Borrowed Stock: {$barang->stok_dipinjam}");
                $this->line("  - Available Stock: {$barang->stok_tersedia}");
                $this->line("");
            });
        }
    }
}
