<?php namespace App\Services\Exchanges\Implementations\Sockets\Traits;

use App\Models\Market;

trait MarketCache
{

    private $markets = [];

    public function addToMarketCache(Market $market)
    {
        $this->markets[strtolower($market->coinPair->name)] = $market;
    }

    public function findMarketById(int $id)
    {
        return collect($this->markets)->first(function($m) use ($id) {
            return $m->id === $id;
        });
    }

    public function findMarketBySymbol(string $symbol)
    {
        return $this->markets[strtolower($symbol)] ?: null;
    }

}
