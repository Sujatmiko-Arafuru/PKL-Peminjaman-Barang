<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\DateHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register DateHelper as a global helper
        if (!function_exists('format_tanggal')) {
            function format_tanggal($date, $includeTime = false) {
                return DateHelper::formatIndonesia($date, $includeTime);
            }
        }
        
        if (!function_exists('format_tanggal_panjang')) {
            function format_tanggal_panjang($date, $includeTime = false) {
                return DateHelper::formatIndonesiaLong($date, $includeTime);
            }
        }
    }
}
