<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('cryptocompare:fetch-coins', function() {
    dispatch(new \App\Jobs\CryptoCompareFetchCoins());
})->describe('Fetches coins from CryptoCompare');

Artisan::command('cryptocompare:fetch-exchanges', function() {
    dispatch(new \App\Jobs\CryptoCompareFetchExchanges());
})->describe('Fetches exchanges from CryptoCompare');

Artisan::command('cryptocompare:fetch-markets', function() {
    dispatch(new \App\Jobs\CryptoCompareFetchMarkets());
})->describe('Fetches markets from CryptoCompare');

Artisan::command('cryptocompare:fetch-historical-prices {exchange?} {--all}', function($exchange = null) {
    dispatch(new \App\Jobs\CryptoCompareFetchHistoricalPricing(
        \App\Models\Exchange::whereInternalName($exchange)->first(),
        null,
        false
    ));
})->describe('Fetches historical prices from CryptoCompare');

Artisan::command('lazy-trader:import-pricing-from-csv {file}', function($file) {
    dispatch(new \App\Jobs\ImportPricingFromCsv($file));
})->describe('Imports Pricing from CSV file template.');
