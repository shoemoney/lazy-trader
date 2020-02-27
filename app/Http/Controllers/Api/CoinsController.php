<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;

class CoinsController extends Controller
{
    // id, name, symbol, # of markets, current aggregate price
    // TODO: pagination
    public function index()
    {
        return Coin::select(['id', 'name', 'symbol', 'is_trading', 'image_url'])
            ->where('is_fiat_currency', false)
            ->get();
    }
}
