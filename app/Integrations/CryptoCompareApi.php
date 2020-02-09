<?php


namespace App\Integrations;


use GuzzleHttp\Client;

class CryptoCompareApi
{
    public function __construct()
    {
        $this->apiKey = config('services.cryptocompare.api-key');

        $this->client = new Client([
            'base_uri' => config('services.cryptocompare.base-uri'),
            'headers' => [
                'Authorization' => 'Apikey ' . $this->apiKey
            ]
        ]);
    }

    public function coins()
    {
        return $this->get('/data/all/coinlist');
    }

    public function exchanges()
    {
        return $this->get('/data/exchanges/general');
    }

    public function markets($exchange = null)
    {
        return $this->get('/data/v2/pair/mapping/exchange', [
            'e' => $exchange
        ]);
    }

    public function histominute($fsym, $tsym, $exchange, $toTs = null, $limit = 2000)
    {
        if (!isset($toTs)) {
            $toTs = now()->timestamp;
        }

        return $this->get('/data/v2/histominute', [
            'fsym' => $fsym,
            'tsym' => $tsym,
            'e' => $exchange,
            'toTs' => $toTs,
            'limit' => $limit
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
