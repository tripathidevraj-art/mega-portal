@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header', 'Admin Dashboard')
@section('subheader', 'System Overview & Analytics')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Pending Users
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $stats['pending_users'] }}
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_users'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Approvals</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['pending_jobs'] + $stats['pending_offers'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Suspended Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['suspended_users'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-slash fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.approval-queue') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-tasks"></i> Approval Queue
                            <span class="badge bg-danger">{{ $stats['pending_jobs'] + $stats['pending_offers'] }}</span>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.expired-content') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-history"></i> Expired Content
                            <span class="badge bg-light text-dark">{{ $stats['expired_jobs'] + $stats['expired_offers'] }}</span>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.users-management') }}" class="btn btn-info btn-block">
                            <i class="fas fa-users-cog"></i> Manage Users
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.user-analytics') }}" class="btn btn-success btn-block">
                            <i class="fas fa-chart-line"></i> User Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Stats -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">System Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded p-3 me-3">
                                <i class="fas fa-briefcase text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0">Total Jobs</p>
                                <h4 class="mb-0">{{ $stats['total_jobs'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded p-3 me-3">
                                <i class="fas fa-shopping-bag text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0">Total Offers</p>
                                <h4 class="mb-0">{{ $stats['total_offers'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded p-3 me-3">
                                <i class="fas fa-hourglass-half text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0">Pending Jobs</p>
                                <h4 class="mb-0">{{ $stats['pending_jobs'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger rounded p-3 me-3">
                                <i class="fas fa-ban text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0">Expired Content</p>
                                <h4 class="mb-0">{{ $stats['expired_jobs'] + $stats['expired_offers'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Recent Activities</h6>
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#users-tab">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#admins-tab">Admins</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Users Tab -->
                    <div class="tab-pane fade show active" id="users-tab">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Reason</th>
                                        <th>By Admin</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userActivities as $activity)
                                        <tr>
                                            <td>
                                                @if($activity->user)
                                                    <strong>{{ $activity->user->full_name }}</strong><br>
                                                    <small class="text-muted">{{ $activity->user->email }}</small>
                                                @else
                                                    <em>Deleted User</em>
                                                @endif
                                            </td>
<td>
    <span class="badge {{ $activity->actionBadgeClass }}">
        {{ ucfirst(str_replace('_', ' ', $activity->action_type)) }}
    </span>
    @if($activity->duration_days)
        <br><small class="text-muted">{{ $activity->duration_days }} days</small>
    @endif
</td>
                                            <td>{{ Str::limit($activity->reason ?? 'No reason', 40) }}</td>
                                            <td>
                                                @if($activity->admin)
                                                    {{ $activity->admin->full_name }}
                                                @else
                                                    <em>System</em>
                                                @endif
                                            </td>
                                            <td title="{{ $activity->created_at->format('M d, Y h:i A') }}">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No user activities yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Admins Tab -->
                    <div class="tab-pane fade" id="admins-tab">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Target User</th>
                                        <th>Action</th>
                                        <th>Reason</th>
                                        <th>Admin</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($adminActions as $action)
                                        <tr>
                                            <td>
                                                @if($action->targetUser)
                                                    <strong>{{ $action->targetUser->full_name }}</strong><br>
                                                    <small class="text-muted">{{ $action->targetUser->email }}</small>
                                                @else
                                                    <em>Unknown User</em>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $action->actionBadgeClass }}">
                                                    {{ ucfirst(str_replace('_', ' ', $action->action)) }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($action->reason ?? '—', 40) }}</td>
                                            <td>
                                                @if($action->admin)
                                                    {{ $action->admin->full_name }}
                                                    <br><small class="text-muted">{{ $action->admin->email }}</small>
                                                @else
                                                    <em>Unknown Admin</em>
                                                @endif
                                            </td>
                                            <td title="{{ $action->created_at->format('M d, Y h:i A') }}">
                                                {{ $action->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No admin actions yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection