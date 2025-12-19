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
                    <button class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#applyModal">
                        <i class="fas fa-paper-plane me-2"></i>Apply Now
                    </button>
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

<!-- Apply Modal -->
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply for: {{ $job->job_title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="applyForm" action="{{ route('jobs.apply', $job->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cover Letter *</label>
                        <textarea class="form-control" name="cover_letter" rows="6" 
                                  placeholder="Tell us why you're the right candidate for this position..." 
                                  required></textarea>
                        <div class="form-text">Minimum 50 characters</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Resume (Optional)</label>
                        <input type="file" class="form-control" name="resume" 
                               accept=".pdf,.doc,.docx">
                        <div class="form-text">Accepted formats: PDF, DOC, DOCX (Max: 2MB)</div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your application will be sent to {{ $job->user->full_name }}. 
                        You'll receive a confirmation email after submission.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span id="applyText">Submit Application</span>
                        <span id="applySpinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </form>
        </div>
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

// Add to scripts section
$('#applyForm').submit(function(e) {
    e.preventDefault();
    
    var btn = $('#applyForm button[type="submit"]');
    var applyText = $('#applyText');
    var spinner = $('#applySpinner');
    
    btn.prop('disabled', true);
    applyText.text('Submitting...');
    spinner.removeClass('d-none');
    
    var formData = new FormData(this);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showToast('success', response.message);
            $('#applyModal').modal('hide');
            setTimeout(() => location.reload(), 2000);
        },
        error: function(xhr) {
            btn.prop('disabled', false);
            applyText.text('Submit Application');
            spinner.addClass('d-none');
            
            showToast('error', xhr.responseJSON?.message || 'An error occurred');
        }
    });
});
</script>
@endpush
@endsection