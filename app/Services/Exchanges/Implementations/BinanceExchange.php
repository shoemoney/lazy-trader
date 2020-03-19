<?php


namespace App\Services\Exchanges\Implementations;


use App\Models\Market;
use App\Services\Exchanges\AbstractExchange;
use App\Services\Exchanges\Implementations\Sockets\BaseExchangeSocketJob;
use App\Services\Exchanges\Implementations\Sockets\BinanceSocket;
use App\Services\Pricing\Ohlc;
use App\Services\Pricing\Ohlcv;

class BinanceExchange extends AbstractExchange
{

    /**
     * @var \ccxt\binance
     */
    private $api;

    public function __construct()
    {
        $this->api = new \ccxt\binance();
        $this->api->loadMarkets();
    }

    /**
     * @inheritDoc
     */
    function minuteOhlc(Market $market, $startTimestamp = null, $endTimestamp = null): iterable
    {
        return Ohlc::parseArray(
            $this->api->fetch_ohlcv(
                $market->coinPair->name_seperated,
                '1m',
                $startTimestamp * 1000,
                ($endTimestamp - $startTimestamp) / 60
            ),
            true
        );
    }

    /**
     * @inheritDoc
     */
    function minuteOhlcv(Market $market, $startTimestamp = null, $endTimestamp = null): iterable
    {
        return Ohlcv::parseArray(
            $this->api->fetch_ohlcv(
                $market->coinPair->name_seperated,
                '1m',
                $startTimestamp * 1000,
                ($endTimestamp - $startTimestamp) / 60
            ),
            true
        );
    }

    /**
     * @inheritDoc
     */
    function minuteOhlcvLimit(): int
    {
        return 1000;
    }

    /**
     * @inheritDoc
     */
    static function internalName(): string
    {
        return 'Binance';
    }

    /**
     * @inheritDoc
     */
    function socket(): BaseExchangeSocketJob
    {
        return new BinanceSocket();
    }
}
