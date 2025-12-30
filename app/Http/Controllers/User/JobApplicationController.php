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
use Illuminate\Support\Str;

class JobApplicationController extends Controller
{
// JobApplicationController.php

public function apply(Request $request, $jobId)
{
    $request->validate([
        'cover_letter' => 'required|string|min:50',
        'resume' => [
            'nullable',
            'file',
            'max:2048',
            'mimes:pdf,doc,docx',
            'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
    ]);

    $job = JobPosting::where('status', 'approved')->findOrFail($jobId);
    
    if ($job->user_id === auth()->id()) {
        return back()->with('error', 'You cannot apply to your own job.');
    }

    if ($job->applications()->where('user_id', auth()->id())->exists()) {
        return back()->with('error', 'You have already applied.');
    }

    // ✅ Sanitize cover letter
    $coverLetter = strip_tags($request->cover_letter);
    $coverLetter = trim(preg_replace('/\s+/', ' ', $coverLetter));

    $data = [
        'job_posting_id' => $job->id,
        'user_id' => auth()->id(),
        'cover_letter' => $coverLetter,
        'status' => 'pending'
    ];

    // ✅ Secure file handling
    if ($request->hasFile('resume')) {
        $path = $request->file('resume')->store('resumes', 'public');
        $data['resume'] = $path;
    }

// After creating application
$application = JobApplication::create($data);

// Load relations (optional but safe)
$application->load('user', 'job.user');

// In controller
try {
    if ($application->user?->email) {
        Mail::to($application->user->email)
            ->send(new JobApplicationConfirmation($application));
    }
} catch (\Exception $e) {
    // Log error but don't stop execution
    \Log::error('Email failed: ' . $e->getMessage());
}

try {
    if ($application->job?->user?->email) {
        Mail::to($application->job->user->email)
            ->send(new JobApplicationReceived($application));
    }
} catch (\Exception $e) {
    \Log::error('Email failed: ' . $e->getMessage());
}

return redirect()->route('jobs.show', $job->id)
                 ->with('success', 'Application submitted securely!');
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
    public function showApplyForm($id)
{
    $job = JobPosting::where('id', $id)
                    ->where('status', 'approved')
                    ->firstOrFail();

    // Apply करने की check (optional)
    if ($job->user_id === auth()->id()) {
        return redirect()->route('jobs.show', $job->id)
                         ->with('error', 'You cannot apply to your own job.');
    }

    if ($job->hasUserApplied()) {
        return redirect()->route('jobs.show', $job->id)
                         ->with('error', 'You have already applied for this job.');
    }

    return view('user.jobs.apply', compact('job'));
}
}