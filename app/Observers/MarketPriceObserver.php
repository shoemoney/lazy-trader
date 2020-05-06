<?php namespace App\Observers;


use App\Events\AggregatePriceChange;
use App\Models\MarketPrice;
use App\Services\Coins\CoinPriceService;
use Carbon\Carbon;

class MarketPriceObserver
{
    /**
     * Handle the market price "created" event.
     *
     * @return void
     */
    public function created(MarketPrice $marketPrice)
    {
        $this->clear($marketPrice);
    }

    /**
     * Handle the market price "updated" event.
     *
     * @return void
     */
    public function updated(MarketPrice $marketPrice)
    {
        $this->clear($marketPrice);
    }

    /**
     * @param MarketPrice $marketPrice
     */
    public function dispatchAggregateCoinPriceClear(MarketPrice $marketPrice): void
    {
//        if ($marketPrice->timestamp > time() - (5 * 60)) {
//            // TODO: Make a dispatched event.
//            \Log::info('market price create ' . $marketPrice->market->coinPair->base->id);
//            CoinPriceService::wipeAggregatePrice($marketPrice->market->coinPair->base);
//            $price = CoinPriceService::aggregatePrice($marketPrice->market->coinPair->base);
//            \Log::info($price);
//            event(new AggregatePriceChange($marketPrice->market->coinPair->base, $price->price));
//        }
    }

    /**
     * @param MarketPrice $marketPrice
     */
    public function clear(MarketPrice $marketPrice): void
    {
        $this->dispatchAggregateCoinPriceClear($marketPrice);

        if (!isset($marketPrice->type) || $marketPrice->type === 'minute') {
            $this->updateHourly($marketPrice);
            $this->updateDaily($marketPrice);
            $this->updateMonthly($marketPrice);
            $this->updateYearly($marketPrice);
        }
    }

    private function updateHourly(MarketPrice $marketPrice)
    {
        \Log::info('update hourly');

        $timestamp = floor($marketPrice->timestamp / 3600) * 3600;

        $this->updateMarketPriceByType($marketPrice, $timestamp, 3600, 'hour');
        \Log::info('done update hourly');

    }

    private function updateDaily(MarketPrice $marketPrice)
    {
        \Log::info('update daily');
        $start = Carbon::createFromTimestamp($marketPrice->timestamp)->startOfDay();
        $end = Carbon::createFromTimestamp($marketPrice->timestamp)->endOfDay();
        $this->updateMarketPriceByType(
            $marketPrice,
            $start->getTimestamp(),
            $end->getTimestamp() - $start->getTimestamp(),
            'day'
        );
        \Log::info('done update daily');

    }

    private function updateMonthly(MarketPrice $marketPrice)
    {
        $start = Carbon::createFromTimestamp($marketPrice->timestamp)->startOfMonth();
        $end = Carbon::createFromTimestamp($marketPrice->timestamp)->endOfMonth();
        $this->updateMarketPriceByType(
            $marketPrice,
            $start->getTimestamp(),
            $end->getTimestamp() - $start->getTimestamp(),
            'month'
        );
    }

    private function updateYearly(MarketPrice $marketPrice)
    {
        $start = Carbon::createFromTimestamp($marketPrice->timestamp)->startOfYear();
        $end = Carbon::createFromTimestamp($marketPrice->timestamp)->endOfYear();
        $this->updateMarketPriceByType(
            $marketPrice,
            $start->getTimestamp(),
            $end->getTimestamp() - $start->getTimestamp(),
            'year'
        );
    }

    /**
     * @param MarketPrice $marketPrice
     * @param $timestamp
     * @param int $timeLength
     * @param string $type
     */
    private function updateMarketPriceByType(MarketPrice $marketPrice, $timestamp, int $timeLength, string $type): void
    {
        $result = MarketPrice::whereMarketId($marketPrice->market_id)
            ->minute()
            ->where('timestamp', '>=', $timestamp)
            ->where('timestamp', '<', $timestamp + $timeLength)
            ->select(
                \DB::raw('(SELECT open FROM market_prices openmp WHERE openmp.timestamp = MIN(market_prices.timestamp) AND openmp.market_id = market_prices.market_id LIMIT 1) as open'),
                \DB::raw('MAX(market_prices.high) as high'),
                \DB::raw('MIN(market_prices.low) as low'),
                \DB::raw('(SELECT close FROM market_prices closemp WHERE closemp.timestamp = MAX(market_prices.timestamp) AND closemp.market_id = market_prices.market_id LIMIT 1) as close'),
                \DB::raw('IFNULL(SUM(market_prices.volume), 0) as volume')
            )
            ->groupBy(['market_id'])
            ->first();

        MarketPrice::updateOrCreate([
            'market_id' => $marketPrice->market_id,
            'timestamp' => $timestamp,
            'type' => $type
        ], [
            'open' => $result->open,
            'high' => $result->high,
            'low' => $result->low,
            'close' => $result->close,
            'volume' => $result->volume,
        ]);
    }
}
