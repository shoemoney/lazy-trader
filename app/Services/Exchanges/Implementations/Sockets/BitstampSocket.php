<?php namespace App\Services\Exchanges\Implementations\Sockets;

use App\Models\Exchange;
use App\Services\Coins\CoinMarketCapService;
use App\Services\Coins\CoinPriceService;
use App\Services\Exchanges\Implementations\Sockets\Traits\ExchangeCache;
use App\Services\Exchanges\Implementations\Sockets\Traits\MarketCache;
use Carbon\Carbon;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

/**
 * TODO: Unknown why, but it seems to stop the socket after about a minute.
 */
class BitstampSocket extends BaseExchangeSocket
{
    use MarketCache, ExchangeCache;

    public $exchangeInternalName = 'Bitstamp';

    public function handleMessage(MessageInterface $msg)
    {
        $data = json_decode($msg);

        \Log::info($msg->__toString());
        if ($data->event === 'trade') {
            $market = $this->findMarketBySymbol(explode('_', $data->channel)[2]);

            $this->importTrade(
                $market,
                $this->nearestMinute($data->data->timestamp),
                floatval($data->data->price),
                floatval($data->data->amount)
            );

        }
    }

    public function handleConnectionOpened()
    {
        $exchange = $this->getExchange();

        foreach ($exchange->markets as $m) {
            $message = [
                'event' => 'bts:subscribe',
                'data' => [
                    'channel' => 'live_trades_' . strtolower($m->coinPair->name)
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
        return 'wss://ws.bitstamp.net/';
    }

    public function handleConnectionException()
    {
        \Log::info('exception');
    }
}
