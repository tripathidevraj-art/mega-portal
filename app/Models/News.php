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
        'views',
    ];

    // Optional: Cast boolean & dates
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

        // Relationships
    public function likes()
    {
        return $this->hasMany(NewsLike::class);
    }

    public function likedByUser()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    // Accessor for total likes
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}