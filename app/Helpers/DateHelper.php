<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format tanggal ke format Indonesia (dd/mm/yyyy)
     */
    public static function formatIndonesia($date, $includeTime = false)
    {
        if (!$date) return '-';
        
        $carbon = Carbon::parse($date);
        
        if ($includeTime) {
            return $carbon->format('d/m/Y H:i');
        }
        
        return $carbon->format('d/m/Y');
    }
    
    /**
     * Format tanggal dengan nama bulan Indonesia
     */
    public static function formatIndonesiaLong($date, $includeTime = false)
    {
        if (!$date) return '-';
        
        $carbon = Carbon::parse($date);
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        $format = $carbon->format('j') . ' ' . $bulan[$carbon->format('n')] . ' ' . $carbon->format('Y');
        
        if ($includeTime) {
            $format .= ' ' . $carbon->format('H:i');
        }
        
        return $format;
    }
    
    /**
     * Format tanggal untuk input date (yyyy-mm-dd)
     */
    public static function formatForInput($date)
    {
        if (!$date) return '';
        
        return Carbon::parse($date)->format('Y-m-d');
    }
    
    /**
     * Format tanggal untuk database (yyyy-mm-dd)
     */
    public static function formatForDatabase($date)
    {
        if (!$date) return null;
        
        return Carbon::parse($date)->format('Y-m-d');
    }
}
