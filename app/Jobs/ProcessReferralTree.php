<?php

namespace App\Jobs;

use App\Models\ReferralCode;
use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessReferralTree implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $referredUserId;
    public $referrerCode;

    public function __construct($referredUserId, $referrerCode)
    {
        $this->referredUserId = $referredUserId;
        $this->referrerCode = $referrerCode;
    }

    public function handle()
    {
        Log::info('🚀 [Referral Job] Started', [
            'referred_user_id' => $this->referredUserId,
            'referrer_code' => $this->referrerCode
        ]);
        $pointsMap = Config::get('referral.points', [1 => 10, 2 => 3, 3 => 1]);
       $maxLevels = Config::get('referral.max_levels', 10);

        // 1. Validate referrer code
        $codeRecord = ReferralCode::where('code', $this->referrerCode)->first();
        if (!$codeRecord) {
            Log::warning('❌ Referrer code not found', ['code' => $this->referrerCode]);
            return;
        }
// After creating Level 1 referral
if ($level == 1) {
    try {
        Mail::to($refId)->send(new ReferralSuccessful(
            $referredUser: User::find($referredId),
            $points: $points
        ));
    } catch (\Exception $e) {
        Log::error('Referral email failed', ['error' => $e->getMessage()]);
    }
}
// Prevent self-referral
if ($referrerId == $referredId) {
    Log::warning('Self-referral blocked', ['user' => $referredId]);
    return;
}

// Prevent same IP abuse (optional)
$referrer = User::find($referrerId);
$referred = User::find($referredId);
if ($referrer && $referred && $referrer->last_login_ip == $referred->last_login_ip) {
    Log::info('Same IP referral - allowed but flagged');
    // You can add manual review logic here
}
        $referrerId = $codeRecord->user_id;
        $referredId = $this->referredUserId;

        if ($referrerId == $referredId) {
            Log::warning('❌ Self-referral blocked', ['user_id' => $referredId]);
            return;
        }

        Log::info('✅ Referrer found', [
            'referrer_id' => $referrerId,
            'referred_id' => $referredId
        ]);

        // 2. Build upline chain
        $chain = [];
        $currentId = $referrerId;
        $maxLevels = 10;

        for ($level = 1; $level <= $maxLevels; $level++) {
            $chain[] = $currentId;
             $points = $pointsMap[$level] ?? 0;
            Log::info("🔗 Level {$level}: Adding user to chain", ['user_id' => $currentId]);

            // Find who referred this user (only Level 1 referral records)
            $parentRef = Referral::where('referred_id', $currentId)
                                 ->where('level', 1)
                                 ->first();

            if (!$parentRef) {
                Log::info("🛑 No parent found for user {$currentId}. Stopping chain.");
                break;
            }

            $currentId = $parentRef->referrer_id;
            if (in_array($currentId, $chain)) {
                Log::warning('⚠️ Cycle detected! Stopping to prevent infinite loop.');
                break;
            }
        }

        Log::info('📊 Chain built', ['chain' => $chain]);

        // 3. Create referral records
        foreach ($chain as $index => $refId) {
            $level = $index + 1;
            $points = match($level) {
                1 => 10,
                2 => 3,
                3 => 1,
                default => 0
            };

            Referral::create([
                'referrer_id' => $refId,
                'referred_id' => $referredId,
                'level' => $level,
                'points_awarded' => $points,
            ]);

            Log::info('✅ Referral record created', [
                'level' => $level,
                'points' => $points,
                'referrer' => $refId,
                'referred' => $referredId
            ]);
        }

        Log::info('🎉 [Referral Job] Completed successfully!');
    }
}