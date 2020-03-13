<?php namespace App\Services\Coins;


use App\Models\Coin;

class CoinService
{
    public static function getFiatMarkets(Coin $coin)
    {
        $coinPair = self::getFiatCoinPair($coin);

        if(!$coinPair) {
            return collect();
        }

        return $coinPair->markets()->get();
    }

    public static function getFiatCoinPair(Coin $coin)
    {
        return $coin->baseCoinPairs()
            ->where('quote_coin_id', self::getUserFiatCoin())
            ->first();
    }

    // TODO: GET USERS BASE CURRENCY
    private static function getUserFiatCoin()
    {
        return Coin::whereSymbol('USD')->first()->id;
    }
}
