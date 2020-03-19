<?php namespace App\Services\Exchanges;

use App\Models\Market;
use App\Services\Exchanges\Implementations\Sockets\BaseExchangeSocketJob;
use App\Services\Pricing\Ohlc;
use App\Services\Pricing\Ohlcv;

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
     * @param Market $market
     * @param integer $startTimestamp
     * @param integer $endTimestamp
     * @return Ohlcv[]
     */
    abstract function minuteOhlcv(Market $market, $startTimestamp = null, $endTimestamp = null): iterable;

    /**
     * Returns the maximum records returned by minuteOhlcv.
     *
     * @return int
     */
    abstract function minuteOhlcvLimit(): int;

    /**
     * @return string
     */
    abstract static function internalName(): string;

    /**
     * @return BaseExchangeSocketJob
     */
    abstract function socket(): BaseExchangeSocketJob;
}
