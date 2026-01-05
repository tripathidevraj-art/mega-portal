<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'action_type',
        'reason',
        'duration_days',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

public function user()
{
    return $this->belongsTo(User::class)->withDefault([
        'full_name' => 'Deleted User'
    ]);
}

public function admin()
{
    return $this->belongsTo(User::class, 'admin_id')->withDefault([
        'full_name' => 'User'
    ]);
}
    
    public function getActionBadgeClassAttribute()
    {
        $classes = [
            'suspended' => 'bg-danger',
            'activated' => 'bg-success',
            'job_posted' => 'bg-info',
            'offer_posted' => 'bg-info',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'profile_updated' => 'bg-warning',
        ];

        return $classes[$this->action_type] ?? 'bg-secondary';
    }
}