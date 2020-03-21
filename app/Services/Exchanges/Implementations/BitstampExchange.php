<?php


namespace App\Services\Exchanges\Implementations;


use App\Models\Market;
use App\Services\Exchanges\AbstractExchange;
use App\Services\Exchanges\Implementations\Sockets\BaseExchangeSocketJob;
use App\Services\Exchanges\Implementations\Sockets\BitstampSocket;
use App\Services\Pricing\Ohlc;
use App\Services\Pricing\Ohlcv;

class BitstampExchange extends AbstractExchange
{

    /**
     * @inheritDoc
     */
    function minuteOhlc(Market $market, $startTimestamp = null, $endTimestamp = null): iterable
    {
        throw new \Exception();
    }

    /**
     * @inheritDoc
     */
    function minuteOhlcv(Market $market, $startTimestamp = null, $endTimestamp = null): iterable
    {
        throw new \Exception();
    }

    /**
     * @inheritDoc
     */
    function minuteOhlcvLimit(): int
    {
        throw new \Exception();
    }

    /**
     * @inheritDoc
     */
    static function internalName(): string
    {
        return 'Bitstamp';
    }

    /**
     * @inheritDoc
     */
    function socket(): BaseExchangeSocketJob
    {
        return new BitstampSocket;
    }
}
