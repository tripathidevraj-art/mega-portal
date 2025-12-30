<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Http\Requests\StoreJobPostingRequest;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::active()->with('user')->latest()->paginate(10);
        return view('user.jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        $job = JobPosting::with('user')->findOrFail($id);

        // Allow:
        // - Public users: only ACTIVE (approved + not expired) jobs
        // - Owners: view ANY of their jobs (pending/rejected/expired)
        if (auth()->check() && $job->user_id === auth()->id()) {
            // Owner can view any job
        } else {
            // Public access: must be approved AND not expired
            if ($job->status !== 'approved' || $job->is_expired) {
                abort(404);
            }
        }

        // Increment views only for public (non-owner) visits
        if (!auth()->check() || $job->user_id !== auth()->id()) {
            $job->increment('views');
        }

        return view('user.jobs.show', compact('job'));
    }

    public function myJobs()
    {
        $jobs = JobPosting::where('user_id', Auth::id())
            ->with('approvedBy')
            ->withCount('applications') 
            ->latest()
            ->paginate(10);
            
        return view('user.jobs.my-jobs', compact('jobs'));
    }

    public function create()
    {
        if (Auth::user()->isSuspended()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Your account is suspended. You cannot post jobs.');
        }
        
        return view('user.jobs.create');
    }

    public function store(StoreJobPostingRequest $request)
    {
        if (Auth::user()->isSuspended()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is suspended.'
            ], 403);
        }

        $job = JobPosting::create([
            'user_id' => Auth::id(),
            'job_title' => $request->job_title,
            'job_description' => $request->job_description,
            'industry' => $request->industry,
            'job_type' => $request->job_type,
            'work_location' => $request->work_location,
            'salary_range' => $request->salary_range,
            'app_deadline' => $request->app_deadline,
            'status' => 'pending',
        ]);

        // Log activity
        UserActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'job_posted',
            'reason' => 'New job posting created',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job posted successfully and awaiting admin approval.',
            'redirect' => route('user.jobs.my-jobs')
        ]);
    }

    public function share($id)
    {
        $job = JobPosting::active()->findOrFail($id);
        
        $shareLinks = [
            'whatsapp' => 'https://wa.me/?text=' . urlencode("Check out this job: {$job->job_title} - " . route('jobs.show', $job->id)),
            'email' => 'mailto:?subject=' . urlencode($job->job_title) . '&body=' . urlencode("Check out this job: " . route('jobs.show', $job->id)),
            'link' => route('jobs.show', $job->id),
        ];
        
        return response()->json([
            'success' => true,
            'links' => $shareLinks
        ]);
    }
}