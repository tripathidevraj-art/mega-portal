<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_title',
        'job_description',
        'industry',
        'job_type',
        'work_location',
        'salary_range',
        'app_deadline',
        'status',
        'approved_by_admin_id',
        'approved_at',
    ];

    protected $casts = [
        'app_deadline' => 'date',
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
                    ->where('app_deadline', '>=', now()->toDateString());
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
                          ->where('app_deadline', '<', now()->toDateString());
                    });
    }

    public function getIsExpiredAttribute()
    {
        return $this->app_deadline < now()->toDateString();
    }

    public function getPublicUrlAttribute()
    {
        return route('jobs.show', $this->id);
    }
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function hasUserApplied($userId = null)
    {
        $userId = $userId ?: auth()->id();
        return $this->applications()->where('user_id', $userId)->exists();
    }
}