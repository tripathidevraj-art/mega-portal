<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\ProductOffer;
use App\Models\UserActivityLog;
use App\Models\AdminActionLog;
use App\Mail\JobApproved;
use App\Mail\JobRejected;
use App\Mail\OfferApproved;
use App\Mail\OfferRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    public function approveJob(Request $request, $id)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $job = JobPosting::findOrFail($id);
        
        DB::transaction(function () use ($job, $request) {
            $job->update([
                'status' => 'approved',
                'approved_by_admin_id' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Log user activity
            UserActivityLog::create([
                'user_id' => $job->user_id,
                'admin_id' => auth()->id(),
                'action_type' => 'approved',
                'reason' => $request->reason,
            ]);

            // Log admin action
            AdminActionLog::create([
                'admin_id' => auth()->id(),
                'action' => 'approved_job',
                'target_user_id' => $job->user_id,
                'target_type' => 'job',
                'target_id' => $job->id,
                'reason' => $request->reason,
            ]);
        });

        // Send email notification
        Mail::to($job->user->email)->send(new JobApproved($job, $request->reason));

        return response()->json([
            'success' => true,
            'message' => 'Job approved successfully'
        ]);
    }

    public function rejectJob(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $job = JobPosting::findOrFail($id);
        
        DB::transaction(function () use ($job, $request) {
            $job->update([
                'status' => 'rejected',
                'approved_by_admin_id' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Log user activity
            UserActivityLog::create([
                'user_id' => $job->user_id,
                'admin_id' => auth()->id(),
                'action_type' => 'rejected',
                'reason' => $request->reason,
            ]);

            // Log admin action
            AdminActionLog::create([
                'admin_id' => auth()->id(),
                'action' => 'rejected_job',
                'target_user_id' => $job->user_id,
                'target_type' => 'job',
                'target_id' => $job->id,
                'reason' => $request->reason,
            ]);
        });

        // Send email notification
        Mail::to($job->user->email)->send(new JobRejected($job, $request->reason));

        return response()->json([
            'success' => true,
            'message' => 'Job rejected successfully'
        ]);
    }

    public function approveOffer(Request $request, $id)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $offer = ProductOffer::findOrFail($id);
        
        DB::transaction(function () use ($offer, $request) {
            $offer->update([
                'status' => 'approved',
                'approved_by_admin_id' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Log user activity
            UserActivityLog::create([
                'user_id' => $offer->user_id,
                'admin_id' => auth()->id(),
                'action_type' => 'approved',
                'reason' => $request->reason,
            ]);

            // Log admin action
            AdminActionLog::create([
                'admin_id' => auth()->id(),
                'action' => 'approved_offer',
                'target_user_id' => $offer->user_id,
                'target_type' => 'offer',
                'target_id' => $offer->id,
                'reason' => $request->reason,
            ]);
        });

        // Send email notification
        Mail::to($offer->user->email)->send(new OfferApproved($offer, $request->reason));

        return response()->json([
            'success' => true,
            'message' => 'Offer approved successfully'
        ]);
    }

    public function rejectOffer(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $offer = ProductOffer::findOrFail($id);
        
        DB::transaction(function () use ($offer, $request) {
            $offer->update([
                'status' => 'rejected',
                'approved_by_admin_id' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Log user activity
            UserActivityLog::create([
                'user_id' => $offer->user_id,
                'admin_id' => auth()->id(),
                'action_type' => 'rejected',
                'reason' => $request->reason,
            ]);

            // Log admin action
            AdminActionLog::create([
                'admin_id' => auth()->id(),
                'action' => 'rejected_offer',
                'target_user_id' => $offer->user_id,
                'target_type' => 'offer',
                'target_id' => $offer->id,
                'reason' => $request->reason,
            ]);
        });

        // Send email notification
        Mail::to($offer->user->email)->send(new OfferRejected($offer, $request->reason));

        return response()->json([
            'success' => true,
            'message' => 'Offer rejected successfully'
        ]);
    }
}