<?php namespace App\Services\Exchanges\Implementations;

use App\Models\Market;
use App\Services\Exchanges\AbstractExchange;
use App\Services\Pricing\Ohlc;
use App\Services\Pricing\Ohlcv;

/**
 * TODO: NOT WORKING.
 */
class GeminiExchange extends AbstractExchange
{

    private static $API_BASE = 'https://api.gemini.com/v2';

    /**
     * @inheritDoc
     */
    function minuteOhlc(Market $market, $startTimestamp = null, $endTimestamp = null): iterable
    {

        if($startTimestamp < time() - ($this->minuteOhlcvLimit() * 60) - 60)
            throw new \Exception('Unable to currently retrieve historical Gemini data.');

        $data = $this->minuteOhlcvCurrent($market);

        return Ohlc::parseArray(
            $data,
            true
        );
    }

    /**
     * @inheritDoc
     */
    function minuteOhlcv(Market $market, $startTimestamp = null, $endTimestamp = null): iterable
    {
        if($startTimestamp < time() - ($this->minuteOhlcvLimit() * 60) - 60)
            throw new \Exception('Unable to currently retrieve historical Gemini data.');

        $data = $this->minuteOhlcvCurrent($market);

        return Ohlcv::parseArray(
            $data,
            true
        );
    }

    /**
     * @inheritDoc
     */
    function minuteOhlcvLimit(): int
    {
        return 1440;
    }

    /**
     * @inheritDoc
     */
    static function internalName(): string
    {
        return 'Gemini';
    }

    /**
     * @param Market $market
     * @return mixed
     */
    private function minuteOhlcvCurrent(Market $market)
    {
        return \Http::get(self::$API_BASE . '/candles/' . $market->coinPair->name . '/1m')->json();
    }

}
