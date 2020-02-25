<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    public $fillable = [
        'symbol', 'name', 'full_name', 'image_url', 'proof_type', 'weiss_rating',
        'weiss_technoology_adoption_rating', 'weiss_performance_rating', 'is_trading'
    ];
}
