# Fitur Upload dan Tampilan Foto Barang

## Deskripsi
Fitur ini memungkinkan admin untuk mengupload maksimal 3 foto untuk setiap barang inventaris. Foto-foto tersebut akan ditampilkan secara interaktif di kedua sisi pengguna (admin dan peminjam) dengan carousel yang responsif.

## Fitur Utama

### 1. Upload Foto Barang
- **Maksimal 3 foto** per barang
- **Format yang didukung**: JPG, JPEG, PNG
- **Ukuran maksimal**: 2MB per foto
- **Fleksibilitas**: Admin bisa upload 1, 2, atau 3 foto sesuai kebutuhan
- **Preview real-time** saat upload

### 2. Tampilan Foto
- **Carousel interaktif** untuk multiple foto
- **Thumbnail** di list barang dengan indikator jumlah foto
- **Responsive design** untuk berbagai ukuran layar
- **Placeholder image** untuk barang tanpa foto

### 3. Integrasi di Kedua Sisi
- **Admin side**: Upload, edit, dan hapus foto
- **Peminjam side**: Lihat foto barang dengan carousel
- **Konsistensi tampilan** di semua halaman

## Struktur Database

### Tabel `barangs`
```sql
ALTER TABLE barangs ADD COLUMN foto1 VARCHAR(255) NULL AFTER deskripsi;
ALTER TABLE barangs ADD COLUMN foto2 VARCHAR(255) NULL AFTER foto1;
ALTER TABLE barangs ADD COLUMN foto3 VARCHAR(255) NULL AFTER foto2;
```

### Model Barang
- `foto1`, `foto2`, `foto3`: Path file foto
- `photos`: Accessor untuk array semua foto
- `main_photo`: Accessor untuk foto utama
- `hasPhotos()`: Method untuk cek apakah ada foto
- `photo_count`: Accessor untuk jumlah foto

## File yang Diperlukan

### 1. Migration
- `2025_01_01_000000_add_foto_columns_to_barangs_table.php`

### 2. Model
- `app/Models/Barang.php` (updated)

### 3. Controller
- `app/Http/Controllers/Admin/InventarisController.php` (updated)

### 4. Views
- `resources/views/admin/inventaris/create.blade.php` (updated)
- `resources/views/admin/inventaris/edit.blade.php` (updated)
- `resources/views/admin/inventaris/show.blade.php` (updated)
- `resources/views/admin/inventaris/index.blade.php` (updated)
- `resources/views/dashboard.blade.php` (updated)
- `resources/views/barang_detail.blade.php` (updated)

### 5. CSS
- `public/assets/css/photo-gallery.css`

### 6. Assets
- `public/assets/images/placeholder-image.svg`

## Cara Penggunaan

### Untuk Admin

#### 1. Tambah Barang Baru
1. Buka halaman "Tambah Barang Inventaris"
2. Isi form data barang
3. Upload foto (maksimal 3) di section "Foto Barang"
4. Preview foto akan muncul secara real-time
5. Klik "Tambah Barang" untuk menyimpan

#### 2. Edit Barang
1. Buka halaman "Edit Barang Inventaris"
2. Foto yang sudah ada akan ditampilkan
3. Upload foto baru untuk mengganti foto lama
4. Kosongkan field jika tidak ingin mengubah foto
5. Klik "Update Barang" untuk menyimpan

#### 3. Lihat Detail Barang
1. Buka halaman "Detail Barang Inventaris"
2. Foto akan ditampilkan dalam carousel jika ada multiple foto
3. Navigasi dengan tombol prev/next atau indicator dots

### Untuk Peminjam

#### 1. Lihat List Barang
1. Buka halaman Dashboard
2. Foto barang akan ditampilkan di card
3. Jika ada multiple foto, akan ada carousel dengan indicator
4. Badge jumlah foto ditampilkan di thumbnail

#### 2. Lihat Detail Barang
1. Klik tombol "Detail" pada card barang
2. Foto akan ditampilkan dalam carousel besar
3. Navigasi dengan tombol prev/next atau indicator dots

## Fitur Teknis

### 1. File Storage
- Foto disimpan di `storage/app/public/barang-photos/`
- Nama file: `timestamp_field_originalname.ext`
- Symbolic link ke `public/storage/` untuk akses publik

### 2. Validasi
- Format file: JPG, JPEG, PNG
- Ukuran maksimal: 2MB
- Field foto bersifat optional

### 3. Carousel Features
- Auto-play dengan interval
- Navigation buttons (prev/next)
- Indicator dots
- Responsive controls
- Smooth transitions

### 4. Responsive Design
- Mobile-friendly carousel controls
- Adaptive image sizing
- Touch-friendly navigation

## Keamanan

### 1. File Upload
- Validasi tipe file
- Pembatasan ukuran file
- Sanitasi nama file
- Storage di folder terpisah

### 2. Access Control
- Hanya admin yang bisa upload/edit foto
- Peminjam hanya bisa melihat foto
- Validasi session dan middleware

## Troubleshooting

### 1. Foto Tidak Muncul
- Pastikan symbolic link storage sudah dibuat: `php artisan storage:link`
- Cek permission folder storage
- Pastikan file ada di lokasi yang benar

### 2. Upload Gagal
- Cek ukuran file (maksimal 2MB)
- Pastikan format file sesuai (JPG/PNG)
- Cek permission folder upload

### 3. Carousel Tidak Berfungsi
- Pastikan Bootstrap JS sudah dimuat
- Cek console browser untuk error JavaScript
- Pastikan ID carousel unik untuk setiap barang

## Maintenance

### 1. Cleanup Foto
- Foto lama otomatis dihapus saat barang dihapus
- Foto lama otomatis dihapus saat diupdate dengan foto baru
- Backup foto penting sebelum maintenance

### 2. Storage Management
- Monitor penggunaan storage
- Implementasi cleanup otomatis untuk foto tidak terpakai
- Backup reguler folder foto

## Future Enhancements

### 1. Fitur Tambahan
- Image compression otomatis
- Multiple image formats (WebP, AVIF)
- Image cropping dan editing
- Bulk upload foto

### 2. Performance
- Lazy loading untuk foto
- Image caching
- CDN integration
- Progressive image loading

### 3. User Experience
- Drag & drop upload
- Image preview sebelum upload
- Image gallery modal
- Zoom dan pan pada foto

## Dependencies

- Laravel 8+
- Bootstrap 5
- PHP GD atau Imagick extension
- Storage driver (local/public)

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)
