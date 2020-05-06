<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{

    public $fillable = ['market_id', 'timestamp', 'type', 'open', 'close', 'high', 'low', 'volume'];

    public $timestamps = false;

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function scopeMinute($query)
    {
        return $query->whereType('minute');
    }

    public function scopeHour($query)
    {
        return $query->whereType('hour');
    }

    public function scopeDay($query)
    {
        return $query->whereType('day');
    }

    public function scopeMonth($query)
    {
        return $query->whereType('month');
    }

    public function scopeYear($query)
    {
        return $query->whereType('year');
    }
}
