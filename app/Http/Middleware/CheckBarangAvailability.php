<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Barang;

class CheckBarangAvailability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika ada barang_id di request, cek ketersediaannya
        if ($request->has('barang_id')) {
            $barangId = $request->input('barang_id');
            $barang = Barang::find($barangId);
            
            if ($barang && !$barang->bisaDipinjam()) {
                return redirect()->back()->with('error', 'Barang tidak tersedia untuk dipinjam.');
            }
        }
        
        return $next($request);
    }
} 