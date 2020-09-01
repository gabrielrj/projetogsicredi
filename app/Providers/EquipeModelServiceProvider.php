<?php

namespace App\Providers;

use App\Models\Equipe;
use App\Observers\EquipeObserver;
use Illuminate\Support\ServiceProvider;

class EquipeModelServiceProvider extends ServiceProvider
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
        Equipe::observe(EquipeObserver::class);
    }
}
