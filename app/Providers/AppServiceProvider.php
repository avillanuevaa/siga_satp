<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Routing\UrlGenerator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        if (env('REDIRECT_HTTPS')) {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('REDIRECT_HTTPS')) {
            $url->formatScheme('https://');
        }

        Schema::defaultStringLength(191);

        Gate::define('verDashboard', function($user) {
            return $user->verDashboard;
        });
        Gate::define('verMantenimientoClasificadores', function($user) {
            return $user->verMantenimientoClasificadores;
        });
        Gate::define('verMantenimientoTrabajadores', function($user) {
            return $user->verMantenimientoTrabajadores;
        });
        Gate::define('verMantenimientoOficinas', function($user) {
            return $user->verMantenimientoOficinas;
        });
        Gate::define('verContabilidadSiaf', function($user) {
            return $user->verContabilidadSiaf;
        });
        Gate::define('verContabilidadExportacion', function($user) {
            return $user->verContabilidadExportacion;
        });
        Gate::define('verRendicionesSolicitudes', function($user) {
            return $user->verRendicionesSolicitudes;
        });
        Gate::define('verRendicionesLiquidaciones', function($user) {
            return $user->verRendicionesLiquidaciones;
        });
        Gate::define('verRendicionesCajaChica', function($user) {
            return $user->verRendicionesCajaChica;
        });
        Gate::define('verRendicionesEncargos', function($user) {
            return $user->verRendicionesEncargos;
        });
        Gate::define('verRendicionesViaticos', function($user) {
            return $user->verRendicionesViaticos;
        });
        Gate::define('verSeguridad', function($user) {
            return $user->verSeguridad;
        });
    }
}
