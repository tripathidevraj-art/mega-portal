<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ReferralCode;
use Illuminate\Support\Facades\DB;

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

public function referralCode()
{
    return $this->hasOne(ReferralCode::class);
}

// Total referral points
public function getTotalReferralPointsAttribute()
{
    return $this->referralsGiven()->sum('points_awarded');
}

// Relationship
public function referralsGiven()
{
    return $this->hasMany(Referral::class, 'referrer_id');
}

// Global rank (cached)
public function getReferralRankAttribute()
{
    return cache()->remember("user_{$this->id}_rank", 3600, function () {
        return DB::table('referrals')
            ->select(DB::raw('COUNT(DISTINCT referrer_id) + 1 as rank'))
            ->where('points_awarded', '>', 
                DB::table('referrals')
                    ->select(DB::raw('SUM(points_awarded)'))
                    ->where('referrer_id', $this->id)
            )->first()->rank;
    });
}
public function getReferralCodeAttribute()
{
    // Use the relationship query, not dynamic property
    $code = $this->referralCode()->first();

    if (!$code) {
        $newCode = strtoupper(bin2hex(random_bytes(4)));
        $code = ReferralCode::create([
            'user_id' => $this->id,
            'code' => $newCode
        ]);
    }

    return $code;
}

public function getReferralTree()
{
    // Recursive method to build tree (for UI)
    return $this->buildTree($this->id);
}

private function buildTree($userId, $depth = 0, $maxDepth = 10)
{
    if ($depth > $maxDepth) return [];

    $directReferrals = User::whereHas('referralsReceived', function ($q) use ($userId) {
        $q->where('referrer_id', $userId)->where('level', 1);
    })->get();

    $tree = [];
    foreach ($directReferrals as $user) {
        $tree[] = [
            'user' => $user,
            'children' => $this->buildTree($user->id, $depth + 1, $maxDepth)
        ];
    }

    return $tree;
}

// For referrals received
public function referralsReceived()
{
    return $this->hasMany(Referral::class, 'referred_id');
}
public function sentInvites()
{
    return $this->hasMany(ReferralInvite::class, 'user_id');
}
}