<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    protected $fillable = ['internal_name', 'name', 'language', 'url', 'image_url'];
}
