<?php

use App\Helpers\DateHelper;

if (!function_exists('format_tanggal')) {
    /**
     * Format tanggal ke format Indonesia (dd/mm/yyyy)
     */
    function format_tanggal($date, $includeTime = false) {
        return DateHelper::formatIndonesia($date, $includeTime);
    }
}

if (!function_exists('format_tanggal_panjang')) {
    /**
     * Format tanggal dengan nama bulan Indonesia
     */
    function format_tanggal_panjang($date, $includeTime = false) {
        return DateHelper::formatIndonesiaLong($date, $includeTime);
    }
}
