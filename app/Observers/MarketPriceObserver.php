<?php namespace App\Observers;


use App\Events\AggregatePriceChange;
use App\Models\MarketPrice;
use App\Services\Coins\CoinPriceService;

class MarketPriceObserver
{
    /**
     * Handle the market price "created" event.
     *
     * @return void
     */
    public function created(MarketPrice $marketPrice)
    {
        $this->dispatchAggregateCoinPriceClear($marketPrice);
    }

    /**
     * Handle the market price "updated" event.
     *
     * @return void
     */
    public function updated(MarketPrice $marketPrice)
    {
        $this->dispatchAggregateCoinPriceClear($marketPrice);
    }

    /**
     * @param MarketPrice $marketPrice
     */
    public function dispatchAggregateCoinPriceClear(MarketPrice $marketPrice): void
    {
        if ($marketPrice->timestamp > time() - (5 * 60)) {
            // TODO: Make a dispatched event.
            \Log::info('market price create ' . $marketPrice->market->coinPair->base->id);
            CoinPriceService::wipeAggregatePrice($marketPrice->market->coinPair->base);
            $price = CoinPriceService::aggregatePrice($marketPrice->market->coinPair->base);
            \Log::info($price);
            event(new AggregatePriceChange($marketPrice->market->coinPair->base, $price->price));
        }
    }
}
