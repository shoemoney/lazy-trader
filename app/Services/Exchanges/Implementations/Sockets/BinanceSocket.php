<?php


namespace App\Services\Exchanges\Implementations\Sockets;


use App\Models\Exchange;
use App\Services\Exchanges\Implementations\Sockets\Traits\ExchangeCache;
use App\Services\Exchanges\Implementations\Sockets\Traits\MarketCache;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

class BinanceSocket extends BaseExchangeSocket
{
    use MarketCache, ExchangeCache;

    protected $exchangeInternalName = 'Binance';

    public function handleMessage(MessageInterface $msg)
    {
        $data = json_decode($msg);

        if(isset($data->stream) && strpos($data->stream, 'kline_1m') !== -1) {
            $kline = $data->data;

            $this->importCandles(
                $this->findMarketBySymbol($kline->s),
                [
                    [$kline->k->t, $kline->k->o, $kline->k->h, $kline->k->l, $kline->k->c, $kline->k->v]
                ]
            );
        }
    }

    public function handleConnectionOpened()
    {
        $exchange = $this->getExchange();

        foreach ($exchange->markets as $m) {
            $message = [
                'method' => 'SUBSCRIBE',
                'params' => [
                    strtolower($m->coinPair->name) . '@kline_1m'
                ],
                'id' => $m->id
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
        return 'wss://stream.binance.com:9443/stream';
    }

    public function handleConnectionException()
    {
        \Log::info('Connection exception');
    }
}
