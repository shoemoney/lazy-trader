<?php namespace App\Services\Exchanges\Implementations\Sockets;


use App\Models\Exchange;
use App\Services\Exchanges\Implementations\Sockets\Traits\ExchangeCache;
use App\Services\Exchanges\Implementations\Sockets\Traits\MarketCache;
use Carbon\Carbon;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

class OkexSocket extends BaseExchangeSocket
{
    use MarketCache, ExchangeCache;

    protected $exchangeInternalName = 'OKEX';

    public function handleMessage(MessageInterface $msg)
    {
        $message = json_decode(gzinflate($msg->__toString()));

        if (isset($message->table) && $message->table === 'spot/candle60s') {
            foreach($message->data as $candle) {
                $market = $this->findMarketBySymbol(str_replace('-', '', $candle->instrument_id));

                if (!$market) continue;

                $this->importCandles(
                    $market,
                    [
                        [$this->nearestMinute(Carbon::parse($candle->candle[0])->getTimestamp()), $candle->candle[1], $candle->candle[2], $candle->candle[3], $candle->candle[4], $candle->candle[5]]
                    ],
                    false
                );
            }
        }
    }

    public function handleConnectionOpened()
    {
        $exchange = $this->getExchange();
        $markets = $exchange->markets()->get()->map(function ($x) {
            return 'spot/candle60s:' . $x->coinPair->name_seperated_coinbase;
        });

        foreach ($exchange->markets as $m) {
            $this->addToMarketCache($m);
        }

        $this->sendMessage(json_encode([
            'op' => 'subscribe',
            'args' => $markets
        ]));


    }

    public function handleConnectionClosed()
    {
        \Log::info('Connection closed');
    }

    public function getConnectionUrl()
    {
        return 'wss://real.OKEx.com:8443/ws/v3';
    }

    public function handleConnectionException()
    {
        \Log::info('Connection exception');
    }
}
