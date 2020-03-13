<?php namespace App\Services\Coins;

use App\Models\Coin;

class CoinMarketCapService
{

    public static $CACHE_KEY_MARKET_CAP = 'coins.market_cap.';

    public static function marketCap(Coin $coin)
    {
        $key = self::$CACHE_KEY_MARKET_CAP . $coin->id;

        return (float) \Cache::remember($key, 180 * 60, function() use ($coin) {
           return  self::computeMarketCap($coin);
        });
    }

    public static function wipeMarketCap(Coin $coin) {
        CoinPriceService::wipeAggregatePrice($coin);
        \Cache::forget(self::$CACHE_KEY_MARKET_CAP . $coin->id);
    }

    private static function computeMarketCap(Coin $coin)
    {
        $marketCap = 0;

        if($coin->circulating_coin_supply !== 0)
            $marketCap = $coin->circulating_coin_supply * optional(CoinPriceService::aggregatePrice($coin))->price;
        else if($coin->total_coin_supply !== 0)
            $marketCap =  $coin->total_coin_supply * optional(CoinPriceService::aggregatePrice($coin))->price;

        \Log::info('Updating market cap for coin ' . $coin->symbol . ' to ' . $marketCap);

        $coin->update(['market_cap' => $marketCap]);
        $coin->save();

        return $marketCap;
    }
}
