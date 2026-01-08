<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralInvite extends Model
{
    protected $fillable = [
        'user_id', 'name', 'contact', 'type', 'referral_code', 'accepted', 'accepted_at'
    ];

    protected $casts = [
        'accepted' => 'boolean',
        'accepted_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
