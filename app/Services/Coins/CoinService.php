<?php namespace App\Services\Coins;


use App\Models\Coin;

class CoinService
{
    public function getFiatMarkets(Coin $coin)
    {
        return $this->getFiatCoinPair($coin)->markets()->get;
    }

    public function getFiatCoinPair(Coin $coin)
    {
        return $coin->baseCoinPairs()
            ->where('quote_coin_id', $this->getUserFiatCoin())
            ->first();
    }

    // TODO: GET USERS BASE CURRENCY
    private function getUserFiatCoin()
    {
        return Coin::whereSymbol('USD')->first()->id;
    }
}
