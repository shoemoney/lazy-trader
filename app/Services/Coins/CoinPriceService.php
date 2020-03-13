<?php namespace App\Services\Coins;


use App\Models\Coin;
use App\Models\MarketPrice;

class CoinPriceService
{
    public static $CACHE_KEY_PRICE = 'coins.aggregate-price.';

    public static function aggregatePrice(Coin $coin)
    {
        $key = self::$CACHE_KEY_PRICE . $coin->id;

        return \Cache::remember($key, 180 * 60, function() use ($coin) {
            return self::computeAggregatePrice($coin);
        });
    }

    public static function wipeAggregatePrice(Coin $coin) {
        \Cache::forget(self::$CACHE_KEY_PRICE . $coin->id);
    }

    private static function computeAggregatePrice(Coin $coin)
    {
        return MarketPrice::select(['timestamp', \DB::raw('(AVG(open) + AVG(close) + AVG(high) + AVG(low)) / 4 as price')])
            ->whereIn('market_id', CoinService::getFiatMarkets($coin)->pluck('id'))
            ->where('timestamp', '>', time() - 60 *60 * 24)
            ->groupBy('timestamp')
            ->orderBy('timestamp', 'DESC')
            ->limit(1)
            ->first();
    }
}
