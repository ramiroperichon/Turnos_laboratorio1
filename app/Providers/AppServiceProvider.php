<?php

namespace App\Providers;

use App\Models\DetalleNegocio;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::hex('#0090e7'),
            'success' => Color::Green,
            'warning' => Color::Amber,
            'secondary' => Color::hex('#8f5fe8'),
        ]);
        $detallenegocioProviders = DetalleNegocio::first();

        View::share('detallenegocioProviders', $detallenegocioProviders);
    }
}
