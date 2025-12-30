@extends('layouts.app')

@section('title', 'Activity Logs')

@section('header', 'Activity Logs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">User & Admin Activity Logs</h6>
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#users-logs-tab">Users Logs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#admins-logs-tab">Admins Logs</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- USERS LOGS TAB -->
                    <div class="tab-pane fade show active" id="users-logs-tab">
                        <!-- Filters -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <input type="text" name="user_search" value="{{ request('user_search') }}" 
                                       class="form-control" placeholder="Search target user...">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="user_admin_search" value="{{ request('user_admin_search') }}" 
                                       class="form-control" placeholder="Search by admin...">
                            </div>
                            <div class="col-md-2">
                                <select name="user_action_type" class="form-select">
                                    <option value="">All Actions</option>
                                    <option value="suspended" {{ request('user_action_type') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="activated" {{ request('user_action_type') == 'activated' ? 'selected' : '' }}>Activated</option>
                                    <option value="approved" {{ request('user_action_type') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('user_action_type') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="user_date_from" value="{{ request('user_date_from') }}" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="user_date_to" value="{{ request('user_date_to') }}" class="form-control">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                            @if(request()->anyFilled(['user_search', 'user_admin_search', 'user_action_type', 'user_date_from', 'user_date_to']))
                                <div class="col-md-1 d-flex align-items-end">
                                    <a href="{{ route('admin.user-logs') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                        </form>

                        <!-- Table -->
                        @if($userLogs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Target User</th>
                                            <th>Action</th>
                                            <th>Reason</th>
                                            <th>By Admin</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userLogs as $log)
                                            <tr>
                                                <td>
                                                    @if($log->user)
                                                        <strong>{{ $log->user->full_name }}</strong><br>
                                                        <small>{{ $log->user->email }}</small>
                                                    @else
                                                        <em>Deleted User</em>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $log->actionBadgeClass }}">
                                                        {{ ucfirst(str_replace('_', ' ', $log->action_type)) }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($log->reason ?? 'No Reason', 50) }}</td>
                                                <td>
                                                    @if($log->admin)
                                                        {{ $log->admin->full_name }}
                                                    @else
                                                        <em>System</em>
                                                    @endif
                                                </td>
                                                <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
<div class="d-flex justify-content-center mt-3">
    {{ $userLogs->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No user activity logs found.</p>
                            </div>
                        @endif
                    </div>

                    <!-- ADMINS LOGS TAB -->
                    <div class="tab-pane fade" id="admins-logs-tab">
                        <!-- Filters -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <input type="text" name="admin_search" value="{{ request('admin_search') }}" 
                                       class="form-control" placeholder="Search target user...">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="admin_admin_search" value="{{ request('admin_admin_search') }}" 
                                       class="form-control" placeholder="Search by admin...">
                            </div>
                            <div class="col-md-2">
                                <select name="admin_action" class="form-select">
                                    <option value="">All Actions</option>
                                    <option value="suspended_user" {{ request('admin_action') == 'suspended_user' ? 'selected' : '' }}>Suspended User</option>
                                    <option value="activated_user" {{ request('admin_action') == 'activated_user' ? 'selected' : '' }}>Activated User</option>
                                    <option value="approved_job" {{ request('admin_action') == 'approved_job' ? 'selected' : '' }}>Approved Job</option>
                                    <option value="rejected_job" {{ request('admin_action') == 'rejected_job' ? 'selected' : '' }}>Rejected Job</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="admin_date_from" value="{{ request('admin_date_from') }}" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="admin_date_to" value="{{ request('admin_date_to') }}" class="form-control">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                            @if(request()->anyFilled(['admin_search', 'admin_admin_search', 'admin_action', 'admin_date_from', 'admin_date_to']))
                                <div class="col-md-1 d-flex align-items-end">
                                    <a href="{{ route('admin.user-logs') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                        </form>

                        <!-- Table -->
                        @if($adminLogs->count() > 0)
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
                                        @foreach($adminLogs as $log)
                                            <tr>
                                                <td>
                                                    @if($log->targetUser)
                                                        <strong>{{ $log->targetUser->full_name }}</strong><br>
                                                        <small>{{ $log->targetUser->email }}</small>
                                                    @else
                                                        <em>Unknown</em>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $log->actionBadgeClass }}">
                                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($log->reason ?? 'No Reason', 50) }}</td>
                                                <td>
                                                    @if($log->admin)
                                                        {{ $log->admin->full_name }}
                                                    @else
                                                        <em>System</em>
                                                    @endif
                                                </td>
                                                <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $adminLogs->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No admin activity logs found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection