<?php

namespace App\Providers;

use App\Models\Cargo;
use App\Observers\CargoObserver;
use Illuminate\Support\ServiceProvider;

class CargoModelServiceProvider extends ServiceProvider
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
        Cargo::observe(CargoObserver::class);
    }
}
