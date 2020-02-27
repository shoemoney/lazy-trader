<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    protected $fillable = ['source_id', 'title', 'body', 'url', 'image_url', 'published_on'];

    protected $dates = ['published_on', 'created_at', 'updated_at'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
