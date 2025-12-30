@extends('layouts.app')

@section('title', $job->job_title)

@section('header', $job->job_title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="mb-1">{{ $job->job_title }}</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-building me-1"></i>{{ $job->industry }}
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $job->job_type == 'full_time' ? 'primary' : ($job->job_type == 'part_time' ? 'success' : 'info') }} fs-6">
                            {{ str_replace('_', ' ', ucfirst($job->job_type)) }}
                        </span>
                        <p class="text-success fw-bold mt-2 mb-0">{{ $job->salary_range }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><i class="fas fa-map-marker-alt me-2"></i> <strong>Location:</strong> {{ $job->work_location }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><i class="fas fa-calendar-alt me-2"></i> <strong>Deadline:</strong> {{ $job->app_deadline->format('F d, Y') }}</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5 class="border-bottom pb-2">Job Description</h5>
                    <p class="mb-0">{{ $job->job_description }}</p>
                </div>
                <!-- Add this after the job description section -->
<div class="card shadow mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">Apply for this Position</h5>
        
        @auth
            @if(auth()->user()->isActive())
                @if($job->hasUserApplied())
                    <div class="alert alert-info">
                        <i class="fas fa-check-circle me-2"></i>
                        You have already applied for this job.
                    </div>
                @elseif($job->user_id === auth()->id())
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        You cannot apply to your own job posting.
                    </div>
                @else
                <a href="{{ route('jobs.apply.form', $job->id) }}" 
                target="_blank" 
                class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-paper-plane me-2"></i>Apply Now
                </a>
                @endif
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Your account needs to be active to apply for jobs.
                </div>
            @endif
        @else
            <div class="alert alert-warning">
                <i class="fas fa-sign-in-alt me-2"></i>
                Please <a href="{{ route('login') }}">login</a> or 
                <a href="{{ route('register') }}">register</a> to apply for this job.
            </div>
        @endauth
    </div>
</div>


                <div class="border-top pt-3">
                    <h6 class="mb-3">Posted By</h6>
                    <div class="d-flex align-items-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-secondary"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">{{ $job->user->full_name }}</p>
                            <p class="mb-0 text-muted">Member since {{ $job->user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Share This Job</h5>
                <div class="share-buttons text-center">
                    <button onclick="shareJob({{ $job->id }})" class="btn btn-success rounded-circle m-1" style="width: 50px; height: 50px;">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    <button onclick="shareJob({{ $job->id }})" class="btn btn-primary rounded-circle m-1" style="width: 50px; height: 50px;">
                        <i class="fas fa-envelope"></i>
                    </button>
                    <button onclick="copyJobLink({{ $job->id }})" class="btn btn-info rounded-circle m-1" style="width: 50px; height: 50px;">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-3">Quick Actions</h5>
                <a href="{{ route('user.jobs.create') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-plus me-2"></i>Post a Job
                </a>
                <a href="{{ route('user.jobs.my-jobs') }}" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="fas fa-list me-2"></i>My Jobs
                </a>
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-briefcase me-2"></i>Browse All Jobs
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function shareJob(jobId) {
    $.ajax({
        url: '/user/jobs/' + jobId + '/share',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                window.open(response.links.whatsapp, '_blank');
            }
        }
    });
}

function copyJobLink(jobId) {
    var link = window.location.origin + '/jobs/' + jobId;
    navigator.clipboard.writeText(link).then(function() {
        alert('Link copied to clipboard!');
    });
}
</script>
@endpush
@endsection