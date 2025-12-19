@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('header', 'Available Jobs')

@section('content')
<div class="row">
    @if($jobs->count() > 0)
        @foreach($jobs as $job)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow job-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $job->job_title }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-building me-1"></i>{{ $job->industry }}
                                </p>
                            </div>
                            <span class="badge bg-{{ $job->job_type == 'full_time' ? 'primary' : ($job->job_type == 'part_time' ? 'success' : 'info') }}">
                                {{ str_replace('_', ' ', ucfirst($job->job_type)) }}
                            </span>
                        </div>
                        
                        <p class="card-text text-muted mb-3">{{ Str::limit($job->job_description, 150) }}</p>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="mb-1"><i class="fas fa-map-marker-alt me-1"></i> Location</p>
                                <small class="text-muted">{{ $job->work_location }}</small>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><i class="fas fa-money-bill-wave me-1"></i> Salary</p>
                                <small class="text-muted">{{ $job->salary_range }}</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>Posted by: {{ $job->user->full_name }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>Deadline: {{ $job->app_deadline->format('M d, Y') }}
                                </small>
                            </div>
                            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $jobs->links() }}
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                    <h4>No Jobs Available</h4>
                    <p class="text-muted">Check back later for new job postings.</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection