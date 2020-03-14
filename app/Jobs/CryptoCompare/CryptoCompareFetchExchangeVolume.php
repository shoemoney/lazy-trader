<?php

namespace App\Jobs\CryptoCompare;

use App\Integrations\CryptoCompareApi;
use App\Models\Coin;
use App\Models\Exchange;
use App\Models\ExchangeVolume;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CryptoCompareFetchExchangeVolume implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = app()->make(CryptoCompareApi::class);
        $exchanges = Exchange::whereNotNull('internal_name')->get();
        $coins = Coin::whereIn('symbol', ['BTC', 'USD'])->get();

        foreach($exchanges as $exchange) {
            \Log::info('Attempting to get volume data for exchange ' . $exchange->name);

            foreach($coins as $coin) {
                \Log::info('Attempting to get volume data for exchange ' . $exchange->name . ':' . $coin->symbol);

                $data = $api->hourlyExchangeVol($exchange->internal_name, $coin->symbol);

                if(!isset($data->Data)) {
                    \Log::error('Unable to get volume data for ' . $exchange->name . ':' . $coin->symbol);
                }

                foreach ($data->Data as $volume) {
                    ExchangeVolume::updateOrCreate([
                        'exchange_id' => $exchange->id,
                        'coin_id' => $coin->id,
                        'timestamp' => $volume->time,
                        'type' => 'HOURLY'
                    ], [
                        'volume' => $volume->volume
                    ]);
                }
            }
        }
    }
}
