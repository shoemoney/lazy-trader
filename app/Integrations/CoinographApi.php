<?php


namespace App\Integrations;


use GuzzleHttp\Client;

class CoinographApi
{
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.coinograph.base-uri')
        ]);
    }

    public function coins($exchange, $quoteSymbol, $baseSymbol, $start, $end)
    {
        return $this->get('/candles', [
            'symbol' => strtolower($exchange . ':' . $quoteSymbol . $baseSymbol),
            'step' => 60,
            'start' => $start,
            'end' => $end
        ]);
    }

    public function get($uri, $query = [])
    {
        // TODO: If we hit our rate limit, throw an exception.
        return json_decode($this->client->get($uri, [
            'query' => $query
        ])->getBody());
    }
}
