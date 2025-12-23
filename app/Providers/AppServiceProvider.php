<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\UKM;
use App\Observers\UKMObserver;

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
        // Register observers
        UKM::observe(UKMObserver::class);
    }
}
