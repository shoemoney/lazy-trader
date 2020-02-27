<?php

namespace App\Jobs\CryptoCompare;

use App\Integrations\CryptoCompareApi;
use App\Models\Market;
use App\Models\MarketPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CryptoCompareFetchHistoricalPricingForMarket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private static $HISTORY_LIMIT = 7 * 24 * 60 * 60;

    /**
     * @var Market
     */
    private $market;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Market $market)
    {
        $this->market = $market;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = app()->make(CryptoCompareApi::class);

        $this->market->load(['coinPair', 'coinPair.quote', 'coinPair.base', 'exchange']);

        $oldestPriceData = MarketPrice::whereMarketId($this->market->id)->orderBy('timestamp')->limit(1)->first();
        $toTs = now()->timestamp;
        if ($oldestPriceData) {
            $toTs = $oldestPriceData->timestamp;
        }

        if ($toTs <= now()->timestamp - static::$HISTORY_LIMIT) {
            \Log::info("We have hit the limit of historical data for this market.");
            return;
        }

        try {

            $prices = $api->histominute(
                $this->market->coinPair->base->symbol,
                $this->market->coinPair->quote->symbol,
                $this->market->exchange->internal_name,
                $toTs
            );

            foreach ($prices->Data->Data as $price) {
                $this->market->prices()->updateOrCreate([
                    'timestamp' => $price->time
                ], [
                    'open' => $price->open,
                    'close' => $price->close,
                    'high' => $price->high,
                    'low' => $price->low
                ]);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
