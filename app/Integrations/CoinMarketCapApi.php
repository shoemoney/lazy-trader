<?php namespace App\Integrations;


class CoinMarketCapApi
{
    private static $API_BASE = 'https://pro-api.coinmarketcap.com';


    public function coinMap()
    {
        return $this->get('/v1/cryptocurrency/map');
    }

    public function coin($symbol = null)
    {
        return $this->get('/v1/cryptocurrency/info', [
            'symbol' => $symbol
        ]);
    }

    private function get($uri, $query = [])
    {
        // TODO: If we hit our rate limit, throw an exception.
        return \Http::withHeaders([
            'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api-key')
        ])->get(self::$API_BASE . $uri, $query)->json();
    }
}
