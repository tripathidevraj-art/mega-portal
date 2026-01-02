@extends('layouts.app')

@section('title', $job->job_title)
@section('header', $job->job_title)
@section('subheader', 'View detailed job information and apply')

@push('styles')
<style>
    .job-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .job-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin: 1rem 0;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    .apply-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    .profile-preview {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }
    .share-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.2s;
    }
    .share-btn:hover {
        transform: translateY(-2px);
    }
    .quick-actions .btn {
        padding: 0.6rem 1rem;
        font-weight: 500;
    }
    .badge-job-type {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
        border-radius: 50px;
    }
    @media (max-width: 768px) {
        .job-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .job-header .text-end {
            margin-top: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <!-- Job Header -->
                <div class="job-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="h2 mb-1">{{ $job->job_title }}</h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-building me-1"></i> {{ $job->industry }}
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge badge-job-type bg-{{ $job->job_type == 'full_time' ? 'primary' : ($job->job_type == 'part_time' ? 'success' : 'info') }}">
                                {{ str_replace('_', ' ', ucfirst($job->job_type)) }}
                            </span>
                            <p class="text-success fw-bold mb-0 mt-2">{{ $job->salary_range }}</p>
                        </div>
                    </div>

                    <div class="job-meta mt-3">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <span>{{ $job->work_location }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar-check text-warning"></i>
                            <span>Deadline: {{ $job->app_deadline->format('F d, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock text-info"></i>
                            <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-users text-success"></i>
                            <span>
                                {{ $job->applications_count ?? 0 }} applicant{{ ($job->applications_count ?? 0) != 1 ? 's' : '' }} already applied
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="mb-4">
                    <h5 class="mb-3 pb-2 border-bottom">Job Description</h5>
                    <div class="text-justify">
                        {!! nl2br(e($job->job_description)) !!}
                    </div>
                </div>

                <!-- Apply Section -->
                <div class="apply-section">
                    <h5 class="mb-3">Apply for this Position</h5>
                    
                    @auth
                        @if(auth()->user()->isActive())
                            @if($job->hasUserApplied())
                                <div class="alert alert-success d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2 fs-4"></i>
                                    <div>You have already applied for this job.</div>
                                </div>
                            @elseif($job->user_id === auth()->id())
                                <div class="alert alert-warning d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2 fs-4"></i>
                                    <div>You cannot apply to your own job posting.</div>
                                </div>
                            @else
                                <a href="{{ route('jobs.apply.form', $job->id) }}" 
                                   class="btn btn-success btn-lg w-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-paper-plane me-2"></i> Apply Now
                                </a>
                            @endif
                            <!-- Report Job Section -->
@if(auth()->check() && auth()->id() !== $job->user_id)
<div class="mt-4 pt-3 border-top">
    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reportJobModal">
        <i class="fas fa-exclamation-triangle me-1"></i> Report This Job
    </button>
</div>

@endif
                        @else
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                                <div>Your account needs to be active to apply for jobs.</div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light border d-flex align-items-center">
                            <i class="fas fa-sign-in-alt me-2 text-primary fs-4"></i>
                            <div>
                                Please <a href="{{ route('login') }}" class="text-decoration-underline">login</a> or 
                                <a href="{{ route('register') }}" class="text-decoration-underline">register</a> to apply.
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Posted By -->
                <div class="pt-3 mt-4 border-top">
                    <h6 class="mb-3">Posted By</h6>
                    <div class="d-flex align-items-center">
                        @if($job->user->profile_image)
                            <img src="{{ asset('storage/' . $job->user->profile_image) }}" 
                                 alt="{{ $job->user->full_name }}" 
                                 class="profile-preview">
                        @else
                            <div class="profile-preview bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-secondary"></i>
                            </div>
                        @endif
                        <div class="ms-3">
                            <p class="mb-0 fw-bold">{{ $job->user->full_name }}</p>
                            <p class="mb-0 text-muted small">Member since {{ $job->user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Share Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Share This Job</h5>
                <div class="d-flex justify-content-center gap-3">
                    <a href="https://wa.me/?text={{ urlencode('Check out this job: ' . $job->job_title . ' - ' . route('jobs.show', $job->id)) }}" 
                       target="_blank" 
                       class="share-btn btn btn-success"
                       title="Share on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="mailto:?subject=Job Opportunity&body={{ urlencode('Check out this job: ' . $job->job_title . ' - ' . route('jobs.show', $job->id)) }}" 
                       class="share-btn btn btn-primary"
                       title="Share via Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <button onclick="copyJobLink()" class="share-btn btn btn-info" title="Copy Link">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('user.jobs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Post a Job
                    </a>
                    <a href="{{ route('user.jobs.my-jobs') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i> My Jobs
                    </a>
                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-briefcase me-2"></i> Browse All Jobs
                    </a>
                </div>
            </div>
        </div>

        <!-- Expiry Warning -->
        @if($job->app_deadline->lt(now()->addDays(3)))
            <div class="alert alert-warning mt-4 d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <small>Deadline is in {{ $job->app_deadline->diffForHumans() }}</small>
            </div>
        @endif
    </div>
</div>
@endsection

<!-- Report Modal -->
<div class="modal fade" id="reportJobModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Job: {{ $job->job_title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('user.jobs.report', $job->id) }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">Help us keep the platform safe by reporting inappropriate content.</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Reason for Report</label>
                        <select name="reason" class="form-select" required>
                            <option value="">Select a reason</option>
                            <option value="inappropriate">Inappropriate Content</option>
                            <option value="fake">Fake or Fraudulent</option>
                            <option value="spam">Spam / Irrelevant</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Additional Details (Optional)</label>
                        <textarea name="details" class="form-control" rows="3" placeholder="Please provide more information..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
function copyJobLink() {
    const link = window.location.href;
    navigator.clipboard.writeText(link).then(() => {
        // Show toast
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">Link copied to clipboard!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 2000 });
        bsToast.show();
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endpush