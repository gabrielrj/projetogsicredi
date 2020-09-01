<?php

namespace App\Providers;

use App\Models\Funcionario;
use App\Observers\FuncionarioObserver;
use Illuminate\Support\ServiceProvider;

class FuncionarioModelServiceProvider extends ServiceProvider
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
        Funcionario::observe(FuncionarioObserver::class);
    }
}
