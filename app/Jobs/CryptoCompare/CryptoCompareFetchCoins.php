<?php

namespace App\Jobs;

use App\Integrations\CryptoCompareApi;
use App\Models\Coin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CryptoCompareFetchCoins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO: Create CryptoCompare Base API
        $api = app()->make(CryptoCompareApi::class);

        $data = $api->coins();
        $baseImageUrl = $data->BaseImageUrl;

        foreach ($data->Data as $coin) {
            if (!$coin->IsTrading) {
                continue;
            }

            Coin::updateOrCreate([
                'symbol' => $coin->Symbol
            ], [
                'name' => $coin->CoinName,
                'full_name' => $coin->FullName,
                'image_url' => isset($coin->ImageUrl) ? $baseImageUrl . $coin->ImageUrl : null,
                'proof_type' => $coin->ProofType,
                'weiss_rating' => !empty($coin->Rating->Weiss->Rating) ? $coin->Rating->Weiss->Rating : null,
                'weiss_technology_adoption_rating' => !empty($coin->Rating->Weiss->TechnologyAdoptionRating) ? $coin->Rating->Weiss->TechnologyAdoptionRating : null,
                'weiss_market_performance_rating' => !empty($coin->Rating->Weiss->MarketPerformanceRating) ? $coin->Rating->Weiss->MarketPerformanceRating : null
            ]);

        }
    }
}
