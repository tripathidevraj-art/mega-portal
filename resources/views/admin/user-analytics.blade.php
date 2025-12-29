@extends('layouts.app')

@section('title', 'User Analytics')

@section('header', 'User Analytics')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <!-- Search -->
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-control" placeholder="Search by name or email">
                    </div>

                    <!-- Status -->
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" placeholder="From">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" placeholder="To">
                    </div>

                    <!-- Sort -->
                    <div class="col-md-2">
                        <select name="sort" class="form-select">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Joined Date</option>
                            <option value="total_jobs" {{ request('sort') == 'total_jobs' ? 'selected' : '' }}>Jobs (High→Low)</option>
                            <option value="total_offers" {{ request('sort') == 'total_offers' ? 'selected' : '' }}>Offers (High→Low)</option>
                            <option value="full_name" {{ request('sort') == 'full_name' ? 'selected' : '' }}>Name</option>
                        </select>
                    </div>

                    <!-- Apply Button -->
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>

                    <!-- Clear Button -->
                    @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to', 'sort']))
                        <div class="col-md-1 d-flex align-items-end">
                            <a href="{{ route('admin.user-analytics') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ $users->total() }} Users Found
                </h6>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Jobs<br><small>(Approved / Total)</small></th>
                                    <th>Offers<br><small>(Approved / Total)</small></th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            @if($user->profile_image)
                                                <img src="{{ Storage::url($user->profile_image) }}" 
                                                     class="rounded-circle me-2" width="30" height="30" alt="">
                                            @endif
                                            <strong>{{ $user->full_name }}</strong><br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{
                                                $user->status === 'verified' || $user->status === 'active' ? 'success' : (
                                                    $user->status === 'pending' ? 'warning' : (
                                                        $user->status === 'suspended' ? 'danger' : 'secondary'
                                                    )
                                                )
                                            }}">
                                                {{ ucfirst($user->status === 'active' ? 'verified' : $user->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->approved_jobs_count }} / {{ $user->total_jobs }}</td>
                                        <td>{{ $user->approved_offers_count }} / {{ $user->total_offers }}</td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.show', $user->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No users found with the selected filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection