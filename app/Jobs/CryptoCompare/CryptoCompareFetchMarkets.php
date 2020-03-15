<?php

namespace App\Jobs\CryptoCompare;

use App\Integrations\CryptoCompareApi;
use App\Models\Coin;
use App\Models\CoinPair;
use App\Models\Exchange;
use App\Models\Market;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CryptoCompareFetchMarkets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle()
    {
        $api = app()->make(CryptoCompareApi::class);

        $result = $api->markets();

        $exchanges = Exchange::select(['id', 'internal_name'])
            ->get()
            ->keyBy('internal_name')
            ->map(function ($x) {
                return $x->id;
            });

        foreach ($result->Data as $exInternalName => $pairs) {
            $exchangeId = $exchanges->get($exInternalName);

            if(empty($exchangeId)) {
                \Log::info('Unable to find exchange: ' . $exInternalName);
                continue;
            }

            foreach($pairs as $pair) {
                $coinPair = $this->fetchCoinPair($pair);

                if(empty($coinPair)) {
                    \Log::info('Unable to find coin pair: ' . $pair->fsym . $pair->tsym);
                    continue;
                }

                Market::updateOrCreate([
                    'exchange_id' => $exchangeId,
                    'coin_pair_id' => $coinPair->id
                ], []);
            }
        }

    }

    private function fetchCoinPair($pair)
    {
        $baseCoin = Coin::whereSymbol($pair->fsym)->first();
        $quoteCoin = Coin::whereSymbol($pair->tsym)->first();

        if(empty($baseCoin)) {
            \Log::info("Unable to find coin: " . $pair->fsym);
            return null;
        }

        if(empty($quoteCoin)) {
            \Log::info("Unable to find coin: " . $pair->tsym);
            return null;
        }

        return CoinPair::firstOrCreate([
            'quote_coin_id' => $quoteCoin->id,
            'base_coin_id' => $baseCoin->id
        ]);
    }
}
