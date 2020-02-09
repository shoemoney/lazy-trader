<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinPair extends Model
{

    public $fillable = ['quote_coin_id', 'base_coin_id'];

    public function getNameAttribute()
    {
        return $this->quote->symbol . $this->base->symbol;
    }

    public function quote()
    {
        return $this->belongsTo(Coin::class, 'quote_coin_id');
    }

    public function base()
    {
        return $this->belongsTo(Coin::class, 'base_coin_id');
    }
}
