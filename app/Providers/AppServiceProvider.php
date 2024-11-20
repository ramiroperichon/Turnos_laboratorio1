<?php

namespace App\Providers;

use App\Models\DetalleNegocio;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Schema;
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
            'muted' => Color::hex('#6c7293'),
        ]);
        try {
            if (Schema::hasTable('detalle_negocios')) {
                $detallenegocioProviders = DetalleNegocio::first();
                if (!$detallenegocioProviders) {
                    $detallenegocioProviders = DetalleNegocio::create([
                        'nombre' => 'Corona',
                        'email' => 'corona@corona.com',
                        'telefono' => '3777332211',
                        'latitud' => '-29.147204842364836',
                        'logitud' => '-59.26232039075694',
                        'direccion' => '1161 Bartolomé Mitre, Goya, Argentina',
                        'Iurl' => 'https://www.instagram.com/maruucorona/',
                        'Furl' => 'https://www.facebook.com/CoronaAustria/?locale=es_LA',
                        'Turl' => 'https://www.tiktok.com/@marvinmelgar145?lang=es',
                        'Xurl' => 'https://x.com/Corona_MX',
                    ]);
                }

                View::share('detallenegocioProviders', $detallenegocioProviders);
            } else {
                View::share('detallenegocioProviders', null);
            }
        } catch (\Exception $e) {
            View::share('detallenegocioProviders', null);
        }
    }
}
