<?php


namespace App\Services\Exchanges\Implementations\Sockets\Traits;


use App\Models\Exchange;

trait ExchangeCache
{
    private $exchange = null;

    public function getExchange(): Exchange
    {
        if (!isset($this->exchangeInternalName)) {
            throw new \Exception('Unable to find Exchange Internal Name');
        }

        if (!isset($this->exchange)) {
            $this->exchange = Exchange::whereInternalName($this->exchangeInternalName)->first();
        }

        return $this->exchange;
    }
}
