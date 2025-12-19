<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_posting_id',
        'user_id',
        'cover_letter',
        'resume',
        'status',
        'admin_notes',
    ];

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeClassAttribute()
    {
        return [
            'pending' => 'bg-warning',
            'reviewed' => 'bg-info',
            'shortlisted' => 'bg-success',
            'rejected' => 'bg-danger',
        ][$this->status] ?? 'bg-secondary';
    }
}