<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job_id',
        'reported_by',
        'reason',
        'details',
        'status'
    ];

    // Reason labels
    public static function getReasonLabel($reason)
    {
        $labels = [
            'inappropriate' => 'Inappropriate Content',
            'fake' => 'Fake or Fraudulent',
            'spam' => 'Spam / Irrelevant',
            'other' => 'Other'
        ];
        return $labels[$reason] ?? $reason;
    }

    // Relationships
    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
    public function getReasonLabelAttribute()
    {
        return self::getReasonLabel($this->reason);
    }
}