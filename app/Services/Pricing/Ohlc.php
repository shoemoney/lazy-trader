<?php namespace App\Services\Pricing;

class Ohlc
{
    /**
     * @var integer
     */
    protected $timestamp;

    /**
     * @var float
     */
    protected $open;

    /**
     * @var float
     */
    protected $high;

    /**
     * @var float
     */
    protected $low;

    /**
     * @var float
     */
    protected $close;

    public function __construct($timestamp, $open, $high, $low, $close)
    {
        $this->timestamp = $timestamp;
        $this->open = $open;
        $this->high = $high;
        $this->low = $low;
        $this->close = $close;
    }

    /**
     * @return integer
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return float
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * @return float
     */
    public function getHigh()
    {
        return $this->high;
    }

    /**
     * @return float
     */
    public function getLow()
    {
        return $this->low;
    }

    /**
     * @return float
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * @param array $data
     * @return Ohlc[]
     */
    public static function parseArray(array $data, $reduceTimestamp = false): iterable
    {
        return array_map(function($x) use ($reduceTimestamp) {
            return new Ohlc($reduceTimestamp ? $x[0] / 1000 : $x[0], $x[1], $x[2], $x[3], $x[4]);
        }, $data);
    }
}
