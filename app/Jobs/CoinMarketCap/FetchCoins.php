<?php

namespace App\Jobs\CoinMarketCap;

use App\Integrations\CoinMarketCapApi;
use App\Models\Coin;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchCoins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var CoinMarketCapApi
     */
    private $api;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->api = app()->make(CoinMarketCapApi::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $symbols = collect($this->api->coinMap()['data'])->map(function ($x) {
            return $x['symbol'];
        })->chunk(50);

        foreach ($symbols as $chunk) {
            $response = $this->api->coin(implode(',', $chunk->toArray()));

            if (!isset($response['data']))
                continue;

            foreach ($response['data'] as $metaData) {
                $coin = Coin::whereSymbol($metaData['symbol'])->first();
                if (!$coin)
                    continue;

                $coin->update([
                    'description' => $metaData['description'] ?: '',
                    'notice' => $metaData['notice'] ?: '',
                    'date_added' => Carbon::parse($metaData['date_added'])
                ]);

                foreach ($metaData['urls'] as $type => $urls) {
                    foreach ($urls as $url) {
                        $coin->urls()->updateOrCreate([
                            'type' => $type,
                            'url' => $url
                        ]);
                    }
                }

                if(empty($metaData['tags']))
                    continue;

                foreach ($metaData['tags'] as $tag) {
                    $tagModel = Tag::updateOrCreate([
                        'tag' => $tag
                    ], []);

                    $coin->tags()->syncWithoutDetaching($tagModel);
                }
            }
        }
    }
}
