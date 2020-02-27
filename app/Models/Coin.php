<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    public $fillable = [
        'symbol', 'name', 'full_name', 'image_url', 'proof_type', 'weiss_rating',
        'weiss_technoology_adoption_rating', 'weiss_performance_rating', 'is_trading'
    ];

    public function baseCoinPairs()
    {
        return $this->hasMany(CoinPair::class, 'base_coin_id');
    }

    public function quoteCoinPairs()
    {
        return $this->hasMany(CoinPair::class, 'quote_coin_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}
