<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    public $fillable = ['name', 'internal_name', 'image_url', 'type', 'description'];

    public function markets()
    {
        return $this->hasMany(Market::class);
    }
}
