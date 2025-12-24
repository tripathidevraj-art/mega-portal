<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

protected $fillable = [
    // Basic Info
    'full_name',
    'email',
    'phone',
    'phone_country_code',
    'whatsapp_country_code',
    'whatsapp',
    'date_of_birth',
    'gender',
    
    // Address
    'country',
    'state',
    'city',
    'zip_code',
    'current_address',
    'communication_address',
    
    // Professional
    'designation',           // renamed from occupation
    'company_name',          // renamed from company
    'industry_experience',
    
    // Documents
    'civil_id',
    'civil_id_file_path',    // new file path
    'passport_number',
    'passport_expiry',
    'residency_type',
    'residency_expiry',
    
    // Volunteer
    'volunteer_interests',
    
    // New Section
    'additional_info',
    
    // Account
    'password',
    'profile_image',
    'role',
    'status',
    'suspension_reason',
    'suspended_until',
    
    // Email Verification
    'email_verified_at',
    'verification_token',
];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'suspended_until' => 'datetime',
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
        'residency_expiry' => 'date',
    ];

    // public function isAdmin()
    // {
    //     return $this->role === 'admin';
    // }

    public function isActive()
    {
        return $this->status === 'active' || $this->status === 'verified';
    }

    public function isVerified()
    {
        return $this->status === 'verified' && !is_null($this->email_verified_at);
    }

    public function isSuspended()
    {
        return $this->status === 'suspended' && 
               ($this->suspended_until === null || $this->suspended_until > now());
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Rest of your model methods remain the same...
    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }

    public function productOffers()
    {
        return $this->hasMany(ProductOffer::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(UserActivityLog::class);
    }

    public function adminActions()
    {
        return $this->hasMany(AdminActionLog::class, 'admin_id');
    }

    public function approvedJobs()
    {
        return $this->hasMany(JobPosting::class, 'approved_by_admin_id');
    }

    public function approvedOffers()
    {
        return $this->hasMany(ProductOffer::class, 'approved_by_admin_id');
    }

    public function getApprovedJobsCountAttribute()
    {
        return $this->jobPostings()->where('status', 'approved')->count();
    }

    public function getApprovedOffersCountAttribute()
    {
        return $this->productOffers()->where('status', 'approved')->count();
    }

    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) return null;
        return now()->diffInYears($this->date_of_birth);
    }
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->role === 'superadmin';
    }
}