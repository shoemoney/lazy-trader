<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinUrl extends Model
{
    protected $fillable = [
        'type', 'url'
    ];
}
