<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Tambahan 1: Wajib di-import agar fungsi URL terbaca

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
        // <-- Tambahan 2: Memaksa Laravel memakai jalur aman HTTPS saat dibuka via Ngrok
        if (env('APP_ENV') !== 'local' || request()->secure() || str_contains(url()->current(), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
    
}