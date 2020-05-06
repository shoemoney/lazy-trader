<?php

namespace App\Providers;

use App\Models\MarketPrice;
use App\Observers\MarketPriceObserver;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);

        MarketPrice::observe(MarketPriceObserver::class);
    }
}
