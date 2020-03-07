<?php


namespace App\Services\Pricing;


class Ohlcv extends Ohlc
{

    /**
     * @var float
     */
    private $volume;

    public function __construct($timestamp, $open, $high, $low, $close, $volume)
    {
        parent::__construct($timestamp, $open, $high, $low, $close);

        $this->volume = $volume;
    }

    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param array $data
     * @return Ohlc[]
     */
    public static function parseArray(array $data, $reduceTimestamp = false): iterable
    {
        return array_map(function($x) use ($reduceTimestamp) {
            return new Ohlcv($reduceTimestamp ? $x[0] / 1000 : $x[0], $x[1], $x[2], $x[3], $x[4], $x[5]);
        }, $data);
    }
}
