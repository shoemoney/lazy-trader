<?php


namespace App\Services\Exchanges\Implementations\Sockets;


use App\Models\Market;
use Ratchet\Client\Connector as ClientConnector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Factory;
use React\Socket\Connector;

/**
 * TODO:
 * - Market caches for exchange
 * - Create async job for import
 */
abstract class BaseExchangeSocket extends BaseExchangeSocketJob
{

    /**
     * @var WebSocket
     */
    private $conn;

    public function start(): void
    {

        \Ratchet\Client\connect($this->getConnectionUrl())
            ->then(function (WebSocket $conn) {
                $conn->on('message', function (MessageInterface $msg) use ($conn) {
                    $this->handleMessage($msg);
                });

                $conn->on('close', function ($code = null, $reason = null) {
                    $this->connectionClosed($code, $reason);
                });

                $this->connectionOpened($conn);
            }, function (\Exception $e) {
                \Log::error($e);
                $this->handleConnectionException();
            });

    }

    private function connectionOpened(WebSocket $conn)
    {
        $this->conn = $conn;
        $this->handleConnectionOpened();
    }

    private function connectionClosed($code, $reason)
    {
        $this->conn = null;
        $this->handleConnectionClosed();
    }


    public function sendMessage(string $message)
    {
        $this->conn->send($message);
    }

    public function importCandles(Market $market, $changes, $reduceTimestamp = true)
    {
        foreach($changes as $data) {
            $market->prices()->updateOrCreate([
                'timestamp' => $reduceTimestamp ? $data[0] / 1000 : $data[0]
            ],[
                'open' => $data[1],
                'high' => $data[2],
                'low' => $data[3],
                'close' => $data[4],
                'volume' => $data[5]
            ]);
        }

    }

    public abstract function handleConnectionOpened();
    public abstract function handleConnectionClosed();
    public abstract function handleConnectionException();
    public abstract function handleMessage(MessageInterface $msg);

    public abstract function getConnectionUrl();
}
