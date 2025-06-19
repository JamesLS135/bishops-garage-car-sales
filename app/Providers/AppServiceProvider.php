<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Car;
use App\Observers\CarObserver;

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
        // Tell Laravel to use the CarObserver to watch the Car model.
        Car::observe(CarObserver::class);
    }
}
