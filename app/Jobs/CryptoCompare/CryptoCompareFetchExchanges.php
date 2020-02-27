<?php

namespace App\Jobs\CryptoCompare;

use App\Integrations\CryptoCompareApi;
use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CryptoCompareFetchExchanges implements ShouldQueue
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

        $result = $api->exchanges();

        $baseImageUrl = 'https://www.cryptocompare.com';

        foreach($result->Data as $exchange) {
            if (!$exchange->Trades)
                continue;

            Exchange::updateOrCreate([
               'name' => $exchange->Name
            ], [
                'internal_name' => $exchange->InternalName,
                'image_url' => $baseImageUrl . $exchange->LogoUrl,
                'type' => $exchange->CentralizationType,
                'description' => $exchange->Description
            ]);
        }
    }
}
