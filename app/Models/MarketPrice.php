<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{

    public $fillable = ['timestamp', 'open', 'close', 'high', 'low'];

    public $timestamps = false;

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
