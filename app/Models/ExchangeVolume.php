<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeVolume extends Model
{
    protected $fillable = ['exchange_id', 'coin_id', 'type', 'timestamp', 'volume'];

    public $timestamps = false;
}
