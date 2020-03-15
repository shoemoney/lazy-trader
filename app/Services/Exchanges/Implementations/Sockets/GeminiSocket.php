<?php


namespace App\Services\Exchanges\Implementations\Sockets;


use App\Models\Exchange;
use App\Services\Exchanges\Implementations\Sockets\Traits\ExchangeCache;
use App\Services\Exchanges\Implementations\Sockets\Traits\MarketCache;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

class GeminiSocket extends BaseExchangeSocket
{
    use MarketCache, ExchangeCache;

    public $exchangeInternalName = 'Gemini';

    public function handleMessage(MessageInterface $msg)
    {
        $data = json_decode($msg);

        if($data->type === 'candles_1m_updates') {
            \Log::info('Received data for ' . $data->symbol);
            $this->importCandles($this->findMarketBySymbol($data->symbol), $data->changes);
        }
    }

    public function handleConnectionOpened()
    {
        $exchange = $this->getExchange();

        foreach($exchange->markets as $m) {
            $message = [
                'type' => 'subscribe',
                'subscriptions' => [
                    [
                        'name' => 'candles_1m',
                        'symbols' => [$m->coinPair->name]
                    ]
                ]
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
        return 'wss://api.gemini.com/v2/marketdata';
    }

    public function handleConnectionException()
    {

    }
}
