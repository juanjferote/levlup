<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Services\NivelService;

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
        // hace que $xpPorcentaje esté disponible en todas las vistas
        // que usan el layout principal, sin repetirlo en cada controlador
        View::composer('layouts.main-layout', function ($view) {
            if (Auth::check()) {
                $datos = app(NivelService::class)->datosProgreso(Auth::user());
                $view->with('xpPorcentaje', $datos['porcentajeNivel']);
            }
        });
    }
}
