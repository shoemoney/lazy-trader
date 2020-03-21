<?php

namespace App\Models;

use App\Services\Coins\CoinPriceService;
use App\Services\Coins\CoinRankingService;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    public $fillable = [
        'symbol', 'name', 'full_name', 'image_url', 'proof_type', 'weiss_rating',
        'weiss_technoology_adoption_rating', 'weiss_performance_rating', 'is_trading',
        'total_coin_supply', 'circulating_coin_supply', 'market_cap'
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

    public function getRankAttribute()
    {
        return CoinRankingService::rank($this);
    }

    public function getPriceAttribute()
    {
        $price = 0;
        $aggPrice = CoinPriceService::aggregatePrice($this);

        if(isset($aggPrice)) {
            $price = $aggPrice->price;
        }

        return $price;
    }

    public function getPriceFormattedAttribute()
    {
        return number_format($this->getPriceAttribute(), 2);
    }

    public function getMarketCapFormattedAttribute()
    {
        return number_format($this->attributes['market_cap'], 2);
    }

    public function getNumMarketsAttribute()
    {
        return $this->baseCoinPairs->sum(function($x) {
            return $x->markets()->count();
        });
    }

    public function scopeOrderByRank($query)
    {
        return $this->orderBy('market_cap', 'DESC')->orderBy('weiss_rating', 'DESC')->orderBy('updated_at', 'DESC');
    }

}
