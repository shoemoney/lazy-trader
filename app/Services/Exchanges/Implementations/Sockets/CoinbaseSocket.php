<?php namespace App\Services\Exchanges\Implementations\Sockets;

use App\Models\Exchange;
use App\Services\Exchanges\Implementations\Sockets\Traits\ExchangeCache;
use App\Services\Exchanges\Implementations\Sockets\Traits\MarketCache;
use Carbon\Carbon;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

/**
 * TODO: Unknown why, but it seems to stop the socket after about a minute.
 */
class CoinbaseSocket extends BaseExchangeSocket
{
    use MarketCache, ExchangeCache;

    public $exchangeInternalName = 'Coinbase';

    public function handleMessage(MessageInterface $msg)
    {
        $data = json_decode($msg);

        if($data->type === 'match' || $data->type === 'last_match') {
            $market = $this->findMarketBySymbol(str_replace('-', '', $data->product_id));
            $time = intval((Carbon::parse($data->time)->getTimestamp() / 60)) * 60;

            $price = $market->prices()->whereTimestamp($time)->first();
            $p = floatval($data->price);

            if(isset($price)) {
                $price->high = max($price->high, $p);
                $price->low = min($price->low, $p);
                $price->close = $p;
                $price->volume = $price->volume + floatval($data->size);
                $price->save();
            } else {
                $market->prices()->create([
                    'timestamp' => $time,
                    'open' => $p,
                    'high' => $p,
                    'low' => $p,
                    'close' => $p,
                    'volume' => floatval($data->size)
                ]);
            }
        }
    }

    public function handleConnectionOpened()
    {
        $exchange = $this->getExchange();

        foreach($exchange->markets as $m) {
            $message = [
                'type' => 'subscribe',
                'channels' => [
                    [
                        'name' => 'matches',
                        'product_ids' => [$m->coinPair->name_seperated_coinbase]
                    ],
                    [
                        'name' => 'heartbeat',
                        'product_ids' => [$m->coinPair->name_seperated_coinbase]
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
        return 'wss://ws-feed.pro.coinbase.com';
    }

    public function handleConnectionException()
    {
        \Log::info('exception');
    }
}
