@extends('layouts.app')

@section('title', 'My Jobs')
@section('header', 'My Posted Jobs')
@section('subheader', 'Manage your job listings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>My Job Postings</h5>
            <a href="{{ route('user.jobs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Post New Job
            </a>
        </div>

        @if($jobs->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                <p class="text-muted">You haven't posted any jobs yet.</p>
                <a href="{{ route('user.jobs.create') }}" class="btn btn-outline-primary">Post Your First Job</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Job Title</th>
                            <th>Industry</th>
                            <th>Applicants</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                        <tr>
                            <td>
                                <a href="{{ route('jobs.show', $job->id) }}" class="text-decoration-none">
                                    <strong>{{ $job->job_title }}</strong>
                                </a>
                                <br>
                                <small class="text-muted">{{ $job->work_location }}</small>
                            </td>
                            <td>{{ $job->industry }}</td>
                            <td>{{ $job->applications_count ?? 0 }}</td>
                            <td>
                                @if($job->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($job->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($job->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">Expired</span>
                                @endif
                            </td>
                            <td>{{ $job->app_deadline->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('user.jobs.job-applications', $job->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-users"></i> View Apps
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection