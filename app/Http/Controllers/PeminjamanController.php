<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function form(): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang masih kosong.');
        }
        return view('peminjaman_form', compact('cart'));
    }

    public function ajukan(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'unit' => 'required|string|max:100',
            'email' => 'required|email',
            'no_telp' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keperluan' => 'required|string|max:255',
            'bukti' => 'required|mimes:pdf|max:2048',
        ]);
        // Simpan data form ke session sementara
        $formData = $request->except('bukti');
        if ($request->hasFile('bukti')) {
            $formData['bukti'] = $request->file('bukti')->store('bukti_peminjaman', 'public');
        }
        $cart = session()->get('cart', []);
        $formData['cart'] = $cart;
        session(['peminjaman_form' => $formData]);
        // Generate OTP
        $otp = random_int(100000, 999999);
        // Simpan OTP ke table otps
        DB::table('otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'kode_otp' => $otp,
                'expired_at' => Carbon::now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        // Kirim OTP ke email
        Mail::to($request->email)->send(new OtpMail($otp, $request->email));
        // Redirect ke halaman verifikasi OTP
        return redirect()->route('peminjaman.verifikasiOtp')->with('info', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function verifikasiOtpForm(): \Illuminate\Contracts\View\View
    {
        return view('verifikasi_otp');
    }

    public function verifikasiOtp(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'kode_otp' => 'required|digits:6',
        ]);
        $formData = session('peminjaman_form');
        if (!$formData) {
            return redirect()->route('peminjaman.form')->with('error', 'Data peminjaman tidak ditemukan.');
        }
        $otpRow = \DB::table('otps')
            ->where('email', $formData['email'])
            ->where('kode_otp', $request->kode_otp)
            ->where('expired_at', '>=', now())
            ->first();
        if (!$otpRow) {
            return back()->withErrors(['kode_otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }
        // Jika OTP valid, simpan data peminjaman
        $kodeUnik = strtoupper(Str::random(8));
        $peminjaman = Peminjaman::create([
            'nama' => $formData['nama'],
            'unit' => $formData['unit'],
            'email' => $formData['email'],
            'no_telp' => $formData['no_telp'],
            'tanggal_mulai' => $formData['tanggal_mulai'],
            'tanggal_selesai' => $formData['tanggal_selesai'],
            'keperluan' => $formData['keperluan'],
            'bukti' => $formData['bukti'],
            'status' => 'menunggu',
            'kode_unik' => $kodeUnik,
        ]);
        // Simpan detail barang yang dipinjam
        foreach ($formData['cart'] as $item) {
            DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'barang_id' => $item['id'],
                'jumlah' => $item['qty'],
            ]);
            // Kurangi stok barang
            $barang = Barang::find($item['id']);
            if ($barang) {
                $barang->stok = max(0, $barang->stok - $item['qty']);
                $barang->save();
            }
        }
        // Hapus session dan OTP
        session()->forget('cart');
        session()->forget('peminjaman_form');
        DB::table('otps')->where('email', $formData['email'])->delete();
        // Kirim kode unik ke email user
        Mail::to($formData['email'])->send(new \App\Mail\OtpMail($kodeUnik, $formData['email']));
        return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil diajukan! Kode unik telah dikirim ke email Anda.');
    }

    public function cekStatusForm(): \Illuminate\Contracts\View\View
    {
        return view('cek_status_form');
    }

    public function cekStatus(Request $request): \Illuminate\Contracts\View\View
    {
        $request->validate([
            'email' => 'required|email',
            'kode_unik' => 'required|string',
        ]);
        $peminjaman = \App\Models\Peminjaman::where('email', $request->email)
            ->where('kode_unik', $request->kode_unik)
            ->first();
        return view('cek_status_hasil', compact('peminjaman'));
    }
} 