<?php

namespace App\Providers;

use App\Models\CarteiraFichaCliente;
use App\Observers\CarteiraFichaClienteObserver;
use Illuminate\Support\ServiceProvider;

class CarteiraFichaClienteModelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        CarteiraFichaCliente::observe(CarteiraFichaClienteObserver::class);
    }
}
