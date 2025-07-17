<x-mail::message>
# Verifikasi Email Peminjaman Barang

Halo,

Kode OTP untuk verifikasi email Anda adalah:

**{{ $kode_otp }}**

Silakan masukkan kode ini pada halaman verifikasi untuk melanjutkan proses peminjaman barang.

Jika Anda tidak meminta kode ini, abaikan email ini.

Terima kasih,
{{ config('app.name') }}
</x-mail::message>
