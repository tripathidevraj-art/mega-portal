<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Mail\JobApplicationReceived;
use App\Mail\JobApplicationConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function apply(StoreJobApplicationRequest $request, $jobId)
    {
        $job = JobPosting::active()->findOrFail($jobId);
        
        // Check if user already applied
        if ($job->hasUserApplied()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this job.'
            ], 400);
        }
        
        // Check if user is applying to their own job
        if ($job->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot apply to your own job posting.'
            ], 400);
        }
        
        $data = $request->validated();
        $data['job_posting_id'] = $job->id;
        $data['user_id'] = Auth::id();
        
        // Handle resume upload
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $data['resume'] = $path;
        }
        
        $application = JobApplication::create($data);
        
        // Send email to job poster
        Mail::to($job->user->email)->send(new JobApplicationReceived($application));
        
        // Send confirmation email to applicant
        Mail::to(Auth::user()->email)->send(new JobApplicationConfirmation($application));
        
        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully! Emails have been sent.'
        ]);
    }
    
    public function myApplications()
    {
        $applications = JobApplication::where('user_id', Auth::id())
            ->with('job')
            ->latest()
            ->paginate(10);
            
        return view('user.jobs.my-applications', compact('applications'));
    }
    
    public function jobApplications($jobId)
    {
        $job = JobPosting::where('user_id', Auth::id())->findOrFail($jobId);
        $applications = $job->applications()->with('user')->latest()->paginate(10);
        
        return view('user.jobs.job-applications', compact('job', 'applications'));
    }
    
    public function updateStatus(Request $request, $applicationId)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $application = JobApplication::findOrFail($applicationId);
        
        // Check if user owns the job
        if ($application->job->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }
        
        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully.'
        ]);
    }
}