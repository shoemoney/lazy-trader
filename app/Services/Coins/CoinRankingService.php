<?php namespace App\Services\Coins;

use App\Models\Coin;

class CoinRankingService
{

    public static function rank(Coin $coin)
    {
        return self::coinIdsByRank()->search(function ($id) use ($coin) {
            return $id === $coin->id;
        }) + 1;
    }

    public static function coinIdsByRank()
    {
        return Coin::orderByRank()->pluck('id');
    }

}
