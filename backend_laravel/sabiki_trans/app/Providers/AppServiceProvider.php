<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use App\Http\Responses\CustomLoginResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

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
    public function boot(Request $request): void
    {
        if ($request->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
