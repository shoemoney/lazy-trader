<?php namespace App\Services\Exchanges;

use App\Services\Exchanges\Implementations\BinanceExchange;
use App\Services\Exchanges\Implementations\GeminiExchange;

class ExchangeFactory
{

    private static $exchanges = [
        BinanceExchange::class,
        GeminiExchange::class
    ];

    public static function create($internalName): AbstractExchange
    {
        foreach(self::$exchanges as $exchange) {
            if($exchange::internalName() === $internalName) {
                return new $exchange();
            }
        }

        throw new \Exception('Unable to find exchange ' . $internalName . ' in ExchangeFactory');
    }
}
