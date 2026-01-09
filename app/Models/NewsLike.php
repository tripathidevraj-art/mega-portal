<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLike extends Model
{
    protected $fillable = ['news_id', 'user_id'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}