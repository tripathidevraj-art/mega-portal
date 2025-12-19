@extends('layouts.app')

@section('title', 'My Applications')

@section('header', 'My Job Applications')

@section('content')
<div class="card shadow">
    <div class="card-body">
        @if($applications->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company/Poster</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->job->job_title }}</strong><br>
                                    <small class="text-muted">{{ $application->job->industry }}</small>
                                </td>
                                <td>{{ $application->job->user->full_name }}</td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $application->status_badge_class }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('jobs.show', $application->job->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        View Job
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $applications->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h4>No Applications Yet</h4>
                <p class="text-muted">You haven't applied for any jobs yet.</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                    <i class="fas fa-briefcase me-2"></i>Browse Jobs
                </a>
            </div>
        @endif
    </div>
</div>
@endsection