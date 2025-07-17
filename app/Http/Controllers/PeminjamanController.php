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
} 