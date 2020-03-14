<?php


namespace App\Services\Exchanges\Implementations\Sockets;


use App\Models\Exchange;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

class BinanceSocket extends BaseExchangeSocket
{


    public function handleMessage(MessageInterface $msg)
    {
        \Log::info($msg);
//        $data = json_decode($msg);
//
//        if ($data->type === 'candles_1m_updates') {
//            \Log::info('Received data for ' . $data->symbol);
//            $this->importCandles($data->symbol, $data->changes);
//        }
//        /*
//         * if (data.type === 'candles_1m_updates' && data.changes.length < 5) {
//            this.importCandles(data.symbol, data.changes);
//        }
//         */
    }

    public function handleConnectionOpened()
    {
        $exchange = Exchange::whereName('Binance')->first();

        foreach ($exchange->markets as $m) {
            $message = [
                "method" => "SUBSCRIBE",
                "params" => [
                    strtolower($m->coinPair->name) . "@kline_1m"
                ],
                'id' => $m->id
            ];


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


    public function getExchange()
    {
        return Exchange::whereInternalName('Gemini')->first();
    }
}
