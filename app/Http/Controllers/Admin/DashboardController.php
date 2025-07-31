<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $menungguApprove = Peminjaman::where('status', 'menunggu')->count();
        $totalPengguna = Peminjaman::distinct('nama')->count('nama');
        $menungguPengembalian = Peminjaman::where('status', 'pengembalian_diajukan')->count();
        $totalBarang = Barang::count();
        $quickApprove = Peminjaman::where('status', 'menunggu')->orderBy('created_at', 'asc')->get();
        return view('admin.dashboard', compact('menungguApprove', 'totalPengguna', 'menungguPengembalian', 'totalBarang', 'quickApprove'));
    }
} 