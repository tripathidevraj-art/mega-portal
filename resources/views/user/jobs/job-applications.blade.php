@extends('layouts.app')

@section('title', 'Applicants for: ' . $job->job_title)
@section('header', 'Manage Applicants')
@section('subheader', $job->job_title)

@push('styles')
<style>
    .applicant-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.3s;
    }
    .applicant-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .doc-badge {
        font-size: 0.85rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
    }
    .status-badge {
        min-width: 100px;
        text-align: center;
    }
    .download-btn {
        font-size: 0.875rem;
        padding: 0.25rem 0.6rem;
    }
    .profile-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Applicants for "{{ $job->job_title }}"</h4>
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar-check me-2"></i> Deadline: {{ $job->app_deadline->format('M d, Y') }} |
                    <i class="fas fa-users me-2"></i> {{ $applications->total() }} {{ Str::plural('applicant', $applications->total()) }}
                </p>
            </div>
            <a href="{{ route('user.jobs.my-jobs') }}" class="btn btn-secondary mt-2 mt-md-0">
                <i class="fas fa-arrow-left me-1"></i> Back to My Jobs
            </a>
        </div>

        @if($applications->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5>No applications yet</h5>
                <p class="text-muted">Share your job to attract qualified candidates!</p>
                <a href="{{ route('user.jobs.share', $job->id) }}" class="btn btn-primary">Share Job</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($applications as $app)
                <div class="col-12">
                    <div class="applicant-card">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <!-- Profile -->
                                <div class="flex-shrink-0">
                                    @if($app->user->profile_image)
                                        <img src="{{ asset('storage/' . $app->user->profile_image) }}" 
                                             alt="{{ $app->user->full_name }}" 
                                             class="rounded-circle" 
                                             width="60" height="60" style="object-fit: cover;">
                                    @else
                                        <div class="profile-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Applicant Info -->
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1">{{ $app->user->full_name }}</h5>
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-envelope me-1"></i> {{ $app->user->email }}
                                                @if($app->user->phone)
                                                    <br><i class="fas fa-phone me-1"></i> {{ $app->user->phone }}
                                                @endif
                                            </p>
                                        </div>
                                        <span class="status-badge badge 
                                            @if($app->status == 'pending') bg-warning text-dark
                                            @elseif($app->status == 'reviewed') bg-info
                                            @elseif($app->status == 'shortlisted') bg-success
                                            @elseif($app->status == 'rejected') bg-danger @endif">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </div>

                                    <!-- Documents -->
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @if($app->resume)
                                            <a href="{{ asset('storage/' . $app->resume) }}" 
                                               target="_blank"
                                               class="btn btn-outline-primary download-btn">
                                                <i class="fas fa-file-pdf me-1"></i> Resume (PDF)
                                            </a>
                                        @else
                                            <span class="badge bg-light text-muted doc-badge">
                                                <i class="fas fa-exclamation-triangle me-1"></i> No Resume
                                            </span>
                                        @endif

                                    <!-- Cover Letter Section -->
                                    @if($app->cover_letter)
                                        <div class="mt-3">
                                            <h6 class="mb-2">Cover Letter</h6>
                                            <div class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                                {!! nl2br(e($app->cover_letter)) !!}
                                            </div>
                                        </div>

                                        @else
                                            <span class="badge bg-light text-muted doc-badge">
                                                <i class="fas fa-info-circle me-1"></i> No Cover Letter
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Applied Date -->
                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-clock me-1"></i> Applied on {{ $app->created_at->format('M d, Y \a\t g:i A') }}
                                    </p>

                                    <!-- Admin Notes (if any) -->
                                    @if($app->admin_notes)
                                        <div class="alert alert-light p-2 mb-3">
                                            <strong>Notes:</strong> {{ $app->admin_notes }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex-shrink-0 align-self-start">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Update Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" action="{{ route('applications.update-status', $app->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="reviewed">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-eye me-1"></i> Mark as Reviewed
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('applications.update-status', $app->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="shortlisted">
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-check-circle me-1"></i> Shortlist
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('applications.update-status', $app->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-times-circle me-1"></i> Reject
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
            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection