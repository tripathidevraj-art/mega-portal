<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_posting_id',
        'user_id',
        'cover_letter',
        'resume',
        'status'
    ];

    // ✅ Relation: Application का owner (applicant)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Relation: Job posting
    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }
}