<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ArsipController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Peminjaman::query();
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai);
        }
        if ($request->filled('urut')) {
            $query->orderBy('created_at', $request->urut == 'terbaru' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        $peminjamans = $query->with('details.barang')->paginate(15);

        // Statistik barang terlaris/tidak pernah dipinjam
        $barangStats = Barang::withCount(['details as total_dipinjam' => function($q) {
            $q->select(DB::raw('sum(jumlah)'));
        }])->get();
        $terlaris = $barangStats->sortByDesc('total_dipinjam')->first();
        $tidakPernah = $barangStats->where('total_dipinjam', null)->all();

        return view('admin.arsip.index', compact('peminjamans', 'terlaris', 'tidakPernah'));
    }

    public function show($id): \Illuminate\View\View
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);
        return view('admin.arsip.show', compact('peminjaman'));
    }
} 