<?php


namespace App\Integrations;


use GuzzleHttp\Client;


/**
 * TODO: Change to L7 HTTP Client
 */
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

    public function hourlyExchangeVol($exchange = null, $tSym = null, $limit = 1000)
    {
        return $this->get('/data/exchange/histohour', [
            'e' => $exchange,
            'tsym' => $tSym,
            'limit' => $limit
        ]);
    }

    public function markets($exchange = null)
    {
        return $this->get('/data/v2/pair/mapping/exchange', [
            'e' => $exchange
        ]);
    }

    public function latestNews($lTs = null, $source = 'ALL_NEWS_FEEDS')
    {
        if ($lTs === null) {
            $lTs = time();
        }
        return $this->get('/data/v2/news/', [
            'lTs' => $lTs,
            'feeds' => $source ?: 'ALL_NEWS_FEEDS'
        ]);
    }

    public function newsSources()
    {
        return $this->get('/data/news/feeds');
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
