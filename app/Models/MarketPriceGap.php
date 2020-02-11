<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPriceGap extends Model
{

    public $fillable = ['gap_timestamp_start', 'gap_timestamp_end'];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
