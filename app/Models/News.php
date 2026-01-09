<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'image',
        'is_published',
        'published_at',
        'admin_id',
    ];

    // Optional: Cast boolean & dates
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];
}