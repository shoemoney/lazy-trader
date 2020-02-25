<?php namespace App\Services\Exchanges;

use App\Models\Market;
use App\Services\Pricing\Ohlc;

abstract class AbstractExchange
{
    /**
     * @param Market $market
     * @param integer $startTimestamp
     * @param integer $endTimestamp
     * @return Ohlc[]
     */
    abstract function minuteOhlc(Market $market, $startTimestamp = null, $endTimestamp = null): iterable;

    /**
     * @return string
     */
    abstract static function internalName(): string;
}
