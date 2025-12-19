<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'target_user_id',
        'target_type',
        'target_id',
        'reason',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function getActionBadgeClassAttribute()
    {
        $classes = [
            'approved_job' => 'bg-success',
            'rejected_job' => 'bg-danger',
            'approved_offer' => 'bg-success',
            'rejected_offer' => 'bg-danger',
            'suspended_user' => 'bg-danger',
            'activated_user' => 'bg-success',
            'updated_user' => 'bg-warning',
        ];

        return $classes[$this->action] ?? 'bg-secondary';
    }
}