<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\User;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data lama kecuali akun admin
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_peminjamans')->truncate();
        DB::table('peminjamans')->truncate();
        DB::table('barangs')->truncate();
        DB::table('users')->truncate();
        DB::table('admins')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Admin
        Admin::create([
            'name' => 'Admin Sarpras',
            'email' => 'admin@sarpras.com',
            'password' => Hash::make('password'),
        ]);

        // Data Dummy Barang
        $barangs = [
            [
                'kode' => 'LAP001',
                'nama' => 'Laptop Dell Inspiron 15',
                'kategori' => 'Elektronik',
                'deskripsi' => 'Laptop untuk keperluan administrasi dan presentasi',
                'stok' => 5,
                'satuan' => 'Unit',
                'lokasi' => 'Gudang A',
                'kondisi' => 'Baik',
                'status' => 'Tersedia'
            ],
            [
                'kode' => 'PRO001',
                'nama' => 'Proyektor Epson EB-X41',
                'kategori' => 'Elektronik',
                'deskripsi' => 'Proyektor untuk presentasi dan pembelajaran',
                'stok' => 3,
                'satuan' => 'Unit',
                'lokasi' => 'Gudang B',
                'kondisi' => 'Baik',
                'status' => 'Tersedia'
            ],
            [
                'kode' => 'MIC001',
                'nama' => 'Microphone Wireless Shure BLX24',
                'kategori' => 'Audio',
                'deskripsi' => 'Microphone wireless untuk acara dan presentasi',
                'stok' => 8,
                'satuan' => 'Unit',
                'lokasi' => 'Gudang A',
                'kondisi' => 'Baik',
                'status' => 'Tersedia'
            ],
            [
                'kode' => 'TAB001',
                'nama' => 'Tablet Samsung Galaxy Tab A8',
                'kategori' => 'Elektronik',
                'deskripsi' => 'Tablet untuk pembelajaran digital',
                'stok' => 4,
                'satuan' => 'Unit',
                'lokasi' => 'Gudang C',
                'kondisi' => 'Baik',
                'status' => 'Tersedia'
            ],
            [
                'kode' => 'CAM001',
                'nama' => 'Kamera DSLR Canon EOS 2000D',
                'kategori' => 'Fotografi',
                'deskripsi' => 'Kamera untuk dokumentasi kegiatan',
                'stok' => 2,
                'satuan' => 'Unit',
                'lokasi' => 'Gudang B',
                'kondisi' => 'Baik',
                'status' => 'Tersedia'
            ],
            [
                'kode' => 'TRIP001',
                'nama' => 'Tripod Kamera Manfrotto MT055',
                'kategori' => 'Fotografi',
                'deskripsi' => 'Tripod untuk kamera dan proyektor',
                'stok' => 6,
                'satuan' => 'Unit',
                'lokasi' => 'Gudang A',
                'kondisi' => 'Baik',
                'status' => 'Tersedia'
            ],
            [
                'kode' => 'SPEAK001',
                'nama' => 'Speaker Portable JBL Flip 5',
                'kategori' => 'Audio',
                'deskripsi' => 'Speaker portable untuk acara outdoor',
                'stok' => 4,
                'satuan' => 'Unit',
                'lokasi' => 'Gudang C',
                'kondisi' => 'Baik',
                'status' => 'Tersedia'
            ],
            [
                'kode' => 'SCREEN001',
                'nama' => 'Layar Proyeksi 100 inch',
                'kategori' => 'Peralatan',
                'deskripsi' => 'Layar proyeksi untuk presentasi',
                'stok' => 3,
                'satuan' => 'Unit',
                'lokasi' => 'Gudang B',
                'kondisi' => 'Baik',
                'status' => 'Tersedia'
            ]
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }

        // Data Dummy Peminjaman
        $peminjamans = [
            [
                'nama' => 'Angelio Asa',
                'no_telp' => '085700385636',
                'unit' => 'Informatika',
                'nama_kegiatan' => 'Hackathon Poltekkes 2025',
                'tanggal_mulai' => '2025-08-14',
                'tanggal_selesai' => '2025-08-31',
                'status' => 'disetujui',
                'kode_peminjaman' => 'ANG-20250814-0001'
            ],
            [
                'nama' => 'Sarah Amelia',
                'no_telp' => '081234567890',
                'unit' => 'Keperawatan',
                'nama_kegiatan' => 'Pelatihan BLS dan ACLS',
                'tanggal_mulai' => '2025-08-20',
                'tanggal_selesai' => '2025-08-25',
                'status' => 'disetujui',
                'kode_peminjaman' => 'SAR-20250820-0001'
            ],
            [
                'nama' => 'Mikhael Rizki',
                'no_telp' => '087654321098',
                'unit' => 'Farmasi',
                'nama_kegiatan' => 'Workshop Teknologi Farmasi',
                'tanggal_mulai' => '2025-08-15',
                'tanggal_selesai' => '2025-08-18',
                'status' => 'pengembalian_diajukan',
                'kode_peminjaman' => 'MIK-20250815-0001'
            ],
            [
                'nama' => 'Anastasia Putri',
                'no_telp' => '089876543210',
                'unit' => 'Gizi',
                'nama_kegiatan' => 'Seminar Nutrisi Seimbang',
                'tanggal_mulai' => '2025-08-10',
                'tanggal_selesai' => '2025-08-12',
                'status' => 'dikembalikan',
                'kode_peminjaman' => 'ANA-20250810-0001'
            ],
            [
                'nama' => 'David Pratama',
                'no_telp' => '081122334455',
                'unit' => 'Kebidanan',
                'nama_kegiatan' => 'Pelatihan Asuhan Kebidanan',
                'tanggal_mulai' => '2025-08-25',
                'tanggal_selesai' => '2025-08-30',
                'status' => 'disetujui',
                'kode_peminjaman' => 'DAV-20250825-0001'
            ],
            [
                'nama' => 'Emma Sari',
                'no_telp' => '087788990011',
                'unit' => 'Kesehatan Lingkungan',
                'nama_kegiatan' => 'Workshop Sanitasi Lingkungan',
                'tanggal_mulai' => '2025-08-22',
                'tanggal_selesai' => '2025-08-24',
                'status' => 'disetujui',
                'kode_peminjaman' => 'EMM-20250822-0001'
            ],
            [
                'nama' => 'James Wilson',
                'no_telp' => '081133445566',
                'unit' => 'Analis Kesehatan',
                'nama_kegiatan' => 'Pelatihan Laboratorium Klinik',
                'tanggal_mulai' => '2025-08-18',
                'tanggal_selesai' => '2025-08-21',
                'status' => 'disetujui',
                'kode_peminjaman' => 'JAM-20250818-0001'
            ]
        ];

        foreach ($peminjamans as $peminjaman) {
            Peminjaman::create($peminjaman);
        }

        // Data Dummy Detail Peminjaman
        $detailPeminjamans = [
            // Angelio Asa - Hackathon
            [
                'peminjaman_id' => 1,
                'barang_id' => 1, // Laptop
                'jumlah' => 2,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 1,
                'barang_id' => 2, // Proyektor
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 1,
                'barang_id' => 3, // Microphone
                'jumlah' => 2,
                'jumlah_dikembalikan' => 0
            ],
            
            // Sarah Amelia - Pelatihan BLS
            [
                'peminjaman_id' => 2,
                'barang_id' => 2, // Proyektor
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 2,
                'barang_id' => 7, // Speaker
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            
            // Mikhael Rizki - Workshop Farmasi
            [
                'peminjaman_id' => 3,
                'barang_id' => 1, // Laptop
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 3,
                'barang_id' => 2, // Proyektor
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 3,
                'barang_id' => 8, // Layar Proyeksi
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            
            // Anastasia Putri - Seminar Gizi (Sudah dikembalikan)
            [
                'peminjaman_id' => 4,
                'barang_id' => 2, // Proyektor
                'jumlah' => 1,
                'jumlah_dikembalikan' => 1
            ],
            [
                'peminjaman_id' => 4,
                'barang_id' => 3, // Microphone
                'jumlah' => 1,
                'jumlah_dikembalikan' => 1
            ],
            
            // David Pratama - Pelatihan Kebidanan
            [
                'peminjaman_id' => 5,
                'barang_id' => 1, // Laptop
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 5,
                'barang_id' => 2, // Proyektor
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            
            // Emma Sari - Workshop Sanitasi
            [
                'peminjaman_id' => 6,
                'barang_id' => 2, // Proyektor
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 6,
                'barang_id' => 8, // Layar Proyeksi
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            
            // James Wilson - Pelatihan Laboratorium
            [
                'peminjaman_id' => 7,
                'barang_id' => 1, // Laptop
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 7,
                'barang_id' => 5, // Kamera
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ],
            [
                'peminjaman_id' => 7,
                'barang_id' => 6, // Tripod
                'jumlah' => 1,
                'jumlah_dikembalikan' => 0
            ]
        ];

        foreach ($detailPeminjamans as $detail) {
            DetailPeminjaman::create($detail);
        }

        // Update stok barang berdasarkan peminjaman
        $this->updateBarangStok();
    }

    private function updateBarangStok()
    {
        $barangs = Barang::all();
        
        foreach ($barangs as $barang) {
            $totalDipinjam = DetailPeminjaman::where('barang_id', $barang->id)
                                            ->whereHas('peminjaman', function($query) {
                                                $query->whereIn('status', ['disetujui', 'pengembalian_diajukan']);
                                            })
                                            ->sum('jumlah');
            
            $totalDikembalikan = DetailPeminjaman::where('barang_id', $barang->id)
                                                ->whereHas('peminjaman', function($query) {
                                                    $query->where('status', 'dikembalikan');
                                                })
                                                ->sum('jumlah_dikembalikan');
            
            $stokTersedia = $barang->stok - $totalDipinjam + $totalDikembalikan;
            
            $barang->update([
                'stok' => $stokTersedia,
                'status' => $stokTersedia > 0 ? 'Tersedia' : 'Habis'
            ]);
        }
    }
}
