<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['details.barang']);

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        // Urutan
        if ($request->filled('urut')) {
            if ($request->urut == 'terlama') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $peminjamans = $query->paginate(10);

        // Data untuk summary - perbaiki relationship yang salah
        $terlaris = Barang::withCount(['peminjamanDetails' => function($query) {
            $query->whereHas('peminjaman', function($q) {
                $q->where('status', 'dikembalikan');
            });
        }])->orderBy('peminjaman_details_count', 'desc')->first();

        $tidakPernah = Barang::whereDoesntHave('peminjamanDetails.peminjaman', function($query) {
            $query->where('status', 'dikembalikan');
        })->get();

        return view('admin.arsip.index', compact('peminjamans', 'terlaris', 'tidakPernah'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['details.barang'])->findOrFail($id);
        return view('admin.arsip.show', compact('peminjaman'));
    }

    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['details.barang']);

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        // Urutan
        if ($request->filled('urut')) {
            if ($request->urut == 'terlama') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $peminjamans = $query->get();

        // Filter info untuk ditampilkan di PDF
        $filterInfo = [];
        if ($request->filled('search')) $filterInfo['search'] = $request->search;
        if ($request->filled('status')) $filterInfo['status'] = $request->status;
        if ($request->filled('tanggal_mulai')) $filterInfo['tanggal_mulai'] = $request->tanggal_mulai;
        if ($request->filled('tanggal_selesai')) $filterInfo['tanggal_selesai'] = $request->tanggal_selesai;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.arsip.pdf', compact('peminjamans', 'filterInfo'));
        
        $filename = 'arsip_peminjaman_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
} 