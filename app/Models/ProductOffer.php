<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_name',
        'category',
        'description',
        'price',
        'discount',
        'expiry_date',
        'product_image',
        'status',
        'approved_by_admin_id',
        'approved_at',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                    ->where('expiry_date', '>=', now()->toDateString());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function($q) {
                        $q->where('status', 'approved')
                          ->where('expiry_date', '<', now()->toDateString());
                    });
    }

    // public function getFinalPriceAttribute()
    // {
    //     return $this->price - ($this->price * ($this->discount / 100));
    // }
    public function getFinalPriceAttribute()
    {
        if ($this->price === null) {
            return null;
        }
        return $this->price - ($this->price * ($this->discount / 100));
    }
    
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date < now()->toDateString();
    }

    public function getPublicUrlAttribute()
    {
        return route('offers.show', $this->id);
    }

}