<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{

    public $fillable = ['exchange_id', 'coin_pair_id'];

    public function getNameAttribute()
    {
        return $this->exchange->name . '/' . $this->coinPair->name;
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function coinPair()
    {
        return $this->belongsTo(CoinPair::class);
    }

    public function prices()
    {
        return $this->hasMany(MarketPrice::class);
    }
}
