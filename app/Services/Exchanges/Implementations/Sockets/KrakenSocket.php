<?php


namespace App\Services\Exchanges\Implementations\Sockets;


use App\Models\Exchange;
use App\Services\Exchanges\Implementations\Sockets\Traits\ExchangeCache;
use App\Services\Exchanges\Implementations\Sockets\Traits\MarketCache;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

class KrakenSocket extends BaseExchangeSocket
{
    use MarketCache, ExchangeCache;

    protected $exchangeInternalName = 'Kraken';

    /**
     * @var array
     */
    private $channels = [];

    public function handleMessage(MessageInterface $msg)
    {
        $data = json_decode($msg);

        if (is_array($data)) {
            if (count($data) === 4 && $data[2] === 'ohlc-1') {
                $market = $this->findMarketBySymbol($this->channels[$data[0]]);

                if (!$market) return;

                $candle = $data[1];

                $this->importCandles(
                    $market,
                    [
                        [$this->nearestMinute($candle[0]), $candle[2], $candle[3], $candle[4], $candle[5], $candle[7]]
                    ],
                    false
                );
            }
        } else if (is_object($data)) {
            if (isset($data->channelName) && $data->channelName === 'ohlc-1') {
                $this->channels[$data->channelID] = $this->simplifyPair($data->pair);
            }
        }
    }

    public function handleConnectionOpened()
    {
        $exchange = $this->getExchange();
        $markets = $exchange->markets()->get()->map(function ($x) {
            return $x->coinPair->name_seperated;
        });

        foreach ($exchange->markets as $m) {
            $this->addToMarketCache($m);
        }

        $this->sendMessage(json_encode([
            'event' => 'subscribe',
            'pair' => $markets,
            'subscription' => [
                'interval' => 1,
                'name' => 'ohlc'
            ]
        ]));


    }

    public function handleConnectionClosed()
    {
        \Log::info('Connection closed');
    }

    public function getConnectionUrl()
    {
        return 'wss://ws.kraken.com/';
    }

    public function handleConnectionException()
    {
        \Log::info('Connection exception');
    }

    private function simplifyPair($pair)
    {
        $pair = str_replace('XBT', 'BTC', $pair); // Replace Bitcoin
        $pair = str_replace('XDG', 'DOGE', $pair); // Replace Doge
        return str_replace('/', '', $pair);
    }
}
