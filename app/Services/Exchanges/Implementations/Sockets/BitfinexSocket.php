<?php


namespace App\Services\Exchanges\Implementations\Sockets;


use App\Models\Exchange;
use App\Services\Exchanges\Implementations\Sockets\Bitfinex\BitfinexSocketStreamGroup;
use App\Services\Exchanges\Implementations\Sockets\Traits\ExchangeCache;
use App\Services\Exchanges\Implementations\Sockets\Traits\MarketCache;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

/**
 * TODO: Non-blocking Stream Groups
 */
class BitfinexSocket extends BaseExchangeSocketJob
{

    use ExchangeCache;

    private const STREAM_LIMIT = 30;

    private $groups = [];

    public $exchangeInternalName = 'Bitfinex';

    public function start(): void
    {
        $exchange = $this->getExchange();
        $chunks = $exchange->markets->chunk(self::STREAM_LIMIT);

        // Create Stream Groups
        foreach($chunks as $group) {
            $this->groups[] = new BitfinexSocketStreamGroup($group);
        }

        // Start Stream Groups
        foreach($this->groups as $group) {
            $group->start();
        }
    }
}
