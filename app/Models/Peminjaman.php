<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPeminjaman;

class Peminjaman extends Model
{
    use HasFactory;
    
    protected $table = 'peminjamans';
    
    protected $fillable = [
        'nama', 'nim_nip', 'foto_peminjam', 'unit', 'no_telp', 'nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'bukti', 'status', 'kode_peminjaman'
    ];

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    /**
     * Get status pengembalian berdasarkan jumlah barang yang dikembalikan
     */
    public function getStatusPengembalianAttribute()
    {
        if ($this->status !== 'disetujui' && $this->status !== 'dipinjam') {
            return $this->status;
        }

        $totalBarang = $this->details->sum('jumlah');
        $totalDikembalikan = $this->details->sum('jumlah_dikembalikan');

        if ($totalDikembalikan == 0) {
            return 'dipinjam';
        } elseif ($totalDikembalikan < $totalBarang) {
            return 'proses_pengembalian';
        } else {
            return 'dikembalikan';
        }
    }

    /**
     * Check if all items are returned
     */
    public function isAllReturned()
    {
        $totalBarang = $this->details->sum('jumlah');
        $totalDikembalikan = $this->details->sum('jumlah_dikembalikan');
        return $totalDikembalikan >= $totalBarang;
    }

    /**
     * Check if partially returned
     */
    public function isPartiallyReturned()
    {
        $totalBarang = $this->details->sum('jumlah');
        $totalDikembalikan = $this->details->sum('jumlah_dikembalikan');
        return $totalDikembalikan > 0 && $totalDikembalikan < $totalBarang;
    }

    /**
     * Get total barang yang dipinjam
     */
    public function getTotalBarangAttribute()
    {
        return $this->details->sum('jumlah');
    }

    /**
     * Get total barang yang sudah dikembalikan
     */
    public function getTotalDikembalikanAttribute()
    {
        return $this->details->sum('jumlah_dikembalikan');
    }

    /**
     * Get total barang yang belum dikembalikan
     */
    public function getTotalBelumDikembalikanAttribute()
    {
        return $this->getTotalBarangAttribute() - $this->getTotalDikembalikanAttribute();
    }

    /**
     * Get percentage of returned items
     */
    public function getPercentageReturnedAttribute()
    {
        $total = $this->getTotalBarangAttribute();
        if ($total == 0) return 0;
        return round(($this->getTotalDikembalikanAttribute() / $total) * 100);
    }

    /**
     * Check if peminjaman can be returned
     */
    public function canBeReturned()
    {
        return in_array($this->status, ['disetujui', 'dipinjam']);
    }

    /**
     * Check if peminjaman is waiting for approval
     */
    public function isWaitingForApproval()
    {
        return $this->status === 'menunggu';
    }

    /**
     * Check if peminjaman is approved
     */
    public function isApproved()
    {
        return $this->status === 'disetujui';
    }

    /**
     * Check if peminjaman is in progress
     */
    public function isInProgress()
    {
        return $this->status === 'dipinjam';
    }

    /**
     * Check if peminjaman is in return process
     */
    public function isInReturnProcess()
    {
        return $this->status === 'proses_pengembalian';
    }

    /**
     * Check if peminjaman is completed
     */
    public function isCompleted()
    {
        return $this->status === 'dikembalikan';
    }
} 