<?php

namespace App\Providers;

use App\Models\Departamento;
use App\Observers\DepartamentoObserver;
use Illuminate\Support\ServiceProvider;

class DepartamentoModelServiceProvider extends ServiceProvider
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
        Departamento::observe(DepartamentoObserver::class);
    }
}
