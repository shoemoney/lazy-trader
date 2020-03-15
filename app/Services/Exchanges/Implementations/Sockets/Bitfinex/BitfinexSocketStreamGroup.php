<?php


namespace App\Services\Exchanges\Implementations\Sockets\Bitfinex;


use App\Services\Exchanges\Implementations\Sockets\BaseExchangeSocket;
use App\Services\Exchanges\Implementations\Sockets\Traits\ExchangeCache;
use App\Services\Exchanges\Implementations\Sockets\Traits\MarketCache;
use Ratchet\RFC6455\Messaging\MessageInterface;

class BitfinexSocketStreamGroup extends BaseExchangeSocket
{
    use MarketCache, ExchangeCache;

    public $exchangeInternalName = 'Bitfinex';

    private $candleChannelCache = [];

    private $bitfinexMarkets;

    public function __construct($bitfinexMarkets = [])
    {
        $this->bitfinexMarkets = $bitfinexMarkets;
    }

    public function handleMessage(MessageInterface $msg)
    {
        $data = json_decode($msg);

        if (isset($data->event)) {
            if ($data->event === 'subscribed' && $data->channel === 'candles') {
                $this->candleChannelCache[$data->chanId] = str_replace('trade:1m:t', '', $data->key);
            }
        } elseif (!is_object($data) && is_array($data)) {
            if ($data[1] === 'hb')
                return;

            if (isset($this->candleChannelCache[$data[0]]) && count($data[1]) > 0) {
                $m = $this->findMarketBySymbol($this->candleChannelCache[$data[0]]);
                $this->importCandles(
                    $m, is_array($data[1][0]) ? $data[1] : [$data[1]]
                );
            }
        }

    }

    public function handleConnectionOpened()
    {
        info('connection opened....');

        foreach ($this->bitfinexMarkets as $m) {
            $message = [
                'event' => 'subscribe',
                'channel' => 'candles',
                'key' => 'trade:1m:t' . $m->coinPair->name
            ];

            $this->addToMarketCache($m);
            $this->sendMessage(json_encode($message));
        }
    }

    public function handleConnectionClosed()
    {
        \Log::info('Connection closed');
    }

    public function getConnectionUrl()
    {
        return 'wss://api-pub.bitfinex.com/ws/2';
    }

    public function handleConnectionException()
    {
        \Log::info('exception');
    }
}
