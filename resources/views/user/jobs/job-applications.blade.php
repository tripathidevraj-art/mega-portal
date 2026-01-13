@extends('layouts.app')

@section('title', 'Applicants for: ' . $job->job_title)
@section('header', 'Manage Applicants')
@section('subheader', $job->job_title)

@push('styles')
<style>
    .applicants-container {
        min-height: calc(100vh - 200px);
        padding: 2rem 0;
    }
    
    .page-header-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
    }
    
    .job-info-badge {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        color: white;
        font-size: 0.875rem;
        padding: 0.375rem 1rem;
        border-radius: 20px;
    }
    
    .applicant-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .applicant-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        border-color: #10b981;
    }
    
    .applicant-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e5e7eb;
    }
    
    .avatar-placeholder {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #10b981;
        border: 3px solid #e5e7eb;
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.375rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        min-width: 110px;
        text-align: center;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    
    .status-reviewed {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }
    
    .status-shortlisted {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    
    .info-item i {
        color: #10b981;
        width: 16px;
    }
    
    .document-badge {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0369a1;
        font-size: 0.8rem;
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .document-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(3, 105, 161, 0.15);
        color: #0369a1;
    }
    
    .document-badge.missing {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #6b7280;
    }
    
    .cover-letter-preview {
        max-height: 150px;
        overflow-y: auto;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 0.9rem;
        line-height: 1.5;
        color: #4b5563;
    }
    
    .action-dropdown .dropdown-menu {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 0.5rem;
        min-width: 200px;
    }
    
    .action-dropdown .dropdown-item {
        padding: 0.625rem 1rem;
        border-radius: 8px;
        margin: 0.125rem 0;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    
    .action-dropdown .dropdown-item:hover {
        background: #f3f4f6;
    }
    
    .btn-update-status {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-update-status:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }
    
    .btn-back {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
    }
    
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        border: 2px dashed #e5e7eb;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }
    
    .admin-notes {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #fbbf24;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .admin-notes strong {
        color: #92400e;
    }
    
    .admin-notes p {
        color: #92400e;
        margin: 0.5rem 0 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    a{
        text-decoration:none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .applicants-container {
            padding: 1rem;
        }
        
        .page-header-card {
            padding: 1.25rem;
        }
        
        .applicant-avatar, .avatar-placeholder {
            width: 60px;
            height: 60px;
            font-size: 1.25rem;
        }
        
        .status-badge {
            min-width: 90px;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="applicants-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        <h3 class="mb-0">Manage Applicants</h3>
                        <span class="job-info-badge">
                            <i class="fas fa-briefcase me-1"></i>{{ $job->industry }}
                        </span>
                    </div>
                    <h2 class="h4 mb-2">{{ $job->job_title }}</h2>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Deadline: {{ $job->app_deadline->format('M d, Y') }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-users"></i>
                            <span>{{ $applications->total() }} {{ Str::plural('applicant', $applications->total()) }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $job->work_location }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('user.jobs.my-jobs') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to My Jobs
                </a>
            </div>
        </div>

        @if($applications->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h3 class="mb-3">No Applications Yet</h3>
                <p class="text-muted mb-4">
                    Share this job posting to attract qualified candidates to your position.
                </p>
                <a href="{{ route('user.jobs.share', $job->id) }}" class="btn-update-status">
                    <i class="fas fa-share-alt me-2"></i>Share Job Posting
                </a>
            </div>
        @else
            <!-- Applicants List -->
            <div class="row g-4">
                @foreach($applications as $application)
                <div class="col-12">
                    <div class="applicant-card py-3 px-3">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row gap-4">
                                <!-- Left: Profile & Basic Info -->
                                <div class="flex-shrink-0 d-flex flex-column align-items-center align-items-md-start">
                                    @if($application->user->profile_image)
                                        <img src="{{ asset('storage/' . $application->user->profile_image) }}" 
                                             alt="{{ $application->user->full_name }}" 
                                             class="applicant-avatar mb-3">
                                    @else
                                        <div class="avatar-placeholder mb-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    
                                    <span class="status-badge status-{{ $application->status }}">
                                        <i class="fas fa-circle fa-xs me-1"></i>
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>

                                <!-- Middle: Details & Documents -->
                                <div class="flex-grow-1">
                                    <!-- Applicant Header -->
                                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                                        <div>
                                            <h4 class="mb-1">{{ $application->user->full_name }}</h4>
                                            <div class="d-flex flex-wrap gap-3 mb-2">
                                                <div class="info-item">
                                                    <i class="fas fa-envelope"></i>
                                                    <span>{{ $application->user->email }}</span>
                                                </div>
                                                @if($application->user->phone)
                                                <div class="info-item">
                                                    <i class="fas fa-phone"></i>
                                                    <span>{{ $application->user->phone }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Applied {{ $application->created_at->diffForHumans() }}
                                        </small>
                                    </div>

                                    <!-- Documents -->
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @if($application->resume)
                                            <a href="{{ asset('storage/' . $application->resume) }}" 
                                               target="_blank"
                                               class="document-badge">
                                                <i class="fas fa-file-pdf"></i>
                                                <span>View Resume</span>
                                            </a>
                                        @else
                                            <span class="document-badge missing">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>No Resume</span>
                                            </span>
                                        @endif
                                    </div>

                                    @if($application->cover_letter)
                                    <div class="mb-3">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success view-cover-letter"
                                                data-bs-toggle="modal"
                                                data-bs-target="#coverLetterModal"
                                                data-name="{{ $application->user->full_name }}"
                                                data-content="{{ htmlspecialchars($application->cover_letter) }}">
                                            <i class="fas fa-envelope-open-text me-1"></i> View Cover Letter
                                        </button>
                                    </div>
                                    @endif

                                    <!-- Admin Notes -->
                                    @if($application->admin_notes)
                                    <div class="admin-notes">
                                        <strong><i class="fas fa-sticky-note me-2"></i>Admin Notes:</strong>
                                        <p class="mb-0">{{ $application->admin_notes }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Right: Actions -->
                                <div class="flex-shrink-0 align-self-start">
                                    <div class="dropdown action-dropdown">
                                        <button class="btn-update-status dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-edit me-2"></i>Update Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" action="{{ route('applications.update-status', $application->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="reviewed">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-eye text-primary"></i>
                                                        <span>Mark as Reviewed</span>
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('applications.update-status', $application->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="shortlisted">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check-circle text-success"></i>
                                                        <span>Shortlist Candidate</span>
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('applications.update-status', $application->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                        <span>Reject Application</span>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($applications->hasPages())
            <div class="mt-5">
                <nav aria-label="Applicants pagination">
                    <ul class="pagination justify-content-center mb-0">
                        {{ $applications->links() }}
                    </ul>
                </nav>
            </div>
            @endif
        @endif
    </div>
</div>
{{-- <!-- Place this AFTER the @endforeach and pagination, but still inside @section('content') --> --}}
<div class="modal fade" id="coverLetterModal" tabindex="-1" aria-labelledby="coverLetterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="coverLetterModalLabel">
                    Cover Letter — <span id="modal-applicant-name"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cover-letter-content" id="modal-cover-letter-content">
                    <!-- Content will be injected here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('coverLetterModal');
    const namePlaceholder = document.getElementById('modal-applicant-name');
    const contentPlaceholder = document.getElementById('modal-cover-letter-content');

    // Listen for modal show event
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const name = button.getAttribute('data-name');
        const content = button.getAttribute('data-content');

        // Set name
        namePlaceholder.textContent = name;

        // Safely render content with line breaks
        contentPlaceholder.innerHTML = content.replace(/\n/g, '<br>');
    });
});
</script>
@endpush