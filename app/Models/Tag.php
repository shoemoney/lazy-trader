<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['tag'];

    public function coins()
    {
        return $this->belongsToMany(Coin::class);
    }

    public function articles()
    {
        return $this->belongsToMany(NewsArticle::class);
    }
}
