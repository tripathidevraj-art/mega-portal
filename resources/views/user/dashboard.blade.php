@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Welcome Back!')
@section('subheader', 'Manage your activity and track your progress')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">My Jobs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['jobs_count'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-briefcase fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">My Offers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['offers_count'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tag fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Applications</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['applications_count'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Profile</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['profile_complete'] }}%</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Jobs -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">My Recent Jobs</h6>
                <a href="{{ route('user.jobs.create') }}" class="btn btn-sm btn-primary">Post New</a>
            </div>
            <div class="card-body">
                @if($recentJobs->isEmpty())
                    <p class="text-muted text-center mb-0">No jobs posted yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Apps</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentJobs as $job)
                                <tr>
                                    <td>
                                        <a href="{{ route('jobs.show', $job->id) }}" class="text-decoration-none">
                                            {{ Str::limit($job->job_title, 20) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($job->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($job->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">Other</span>
                                        @endif
                                    </td>
                                    <td>{{ $job->applications_count ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Offers -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">My Recent Offers</h6>
                <a href="{{ route('user.offers.create') }}" class="btn btn-sm btn-success">Post New</a>
            </div>
            <div class="card-body">
                @if($recentOffers->isEmpty())
                    <p class="text-muted text-center mb-0">No offers posted yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOffers as $offer)
                                <tr>
                                    <td>
                                        <a href="{{ route('offers.show', $offer->id) }}" class="text-decoration-none">
                                            {{ Str::limit($offer->product_name, 20) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($offer->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($offer->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">Other</span>
                                        @endif
                                    </td>
                                    <td>{{ $offer->views }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection