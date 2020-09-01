<?php

namespace App\Providers;

use App\Models\Empresa;
use App\Observers\EmpresaObserver;
use Illuminate\Support\ServiceProvider;

class EmpresaModelServiceProvider extends ServiceProvider
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
        Empresa::observe(EmpresaObserver::class);
    }
}
