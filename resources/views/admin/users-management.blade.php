@extends('layouts.app')

@section('title', 'Manage Users')

@section('header', 'User Management')
@section('subheader', 'Approve, suspend, or review user accounts')
@push('styles')
<style>
    .pagination {
        display: flex;
        justify-content: center;
    }

    .page-item .page-link {
        color: #4361ee;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.25rem;
    }

    .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
        color: white;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }

    .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
    border-color: #e2e8f0 !important;
}
</style>
@endpush
@section('content')
<div class="row">
<div class="row g-3 mb-4">
    <!-- Verified -->
    <div class="col-6 col-md-2">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'verified', 'page' => 1]) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 bg-light hover-card">
                <div class="card-body text-center p-3">
                    <div class="icon-circle bg-success text-white mx-auto mb-2">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="text-muted small fw-bold">Verified</div>
                    <div class="fs-5 fw-bold text-success">{{ $stats['verified'] }}</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Pending Approval -->
    <div class="col-6 col-md-2">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending', 'email_verified' => 'verified', 'page' => 1]) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 bg-light hover-card">
                <div class="card-body text-center p-3">
                    <div class="icon-circle bg-warning text-white mx-auto mb-2">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="text-muted small fw-bold">Pending</div>
                    <div class="fs-5 fw-bold text-warning">{{ $stats['pending_verified'] }}</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Unverified (Email Not Verified) -->
    <div class="col-6 col-md-2">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending', 'email_verified' => 'unverified', 'page' => 1]) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 bg-light hover-card">
                <div class="card-body text-center p-3">
                    <div class="icon-circle bg-secondary text-white mx-auto mb-2">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="text-muted small fw-bold">Unverified</div>
                    <div class="fs-5 fw-bold text-secondary">{{ $stats['unverified'] }}</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Rejected -->
    <div class="col-6 col-md-2">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected', 'page' => 1]) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 bg-light hover-card">
                <div class="card-body text-center p-3">
                    <div class="icon-circle bg-dark text-white mx-auto mb-2">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="text-muted small fw-bold">Rejected</div>
                    <div class="fs-5 fw-bold text-dark">{{ $stats['rejected'] }}</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Suspended -->
    <div class="col-6 col-md-2">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'suspended', 'page' => 1]) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 bg-light hover-card">
                <div class="card-body text-center p-3">
                    <div class="icon-circle bg-danger text-white mx-auto mb-2">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div class="text-muted small fw-bold">Suspended</div>
                    <div class="fs-5 fw-bold text-danger">{{ $stats['suspended'] }}</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total (Clear Filters) -->
    <div class="col-6 col-md-2">
        <a href="{{ route('admin.users-management') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 bg-light hover-card">
                <div class="card-body text-center p-3">
                    <div class="icon-circle bg-primary text-white mx-auto mb-2">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="text-muted small fw-bold">Total</div>
                    <div class="fs-5 fw-bold text-primary">
                        {{ $stats['verified'] + $stats['pending_verified'] + $stats['rejected'] + $stats['suspended'] }}
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
</div>
        <div class="card shadow">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">All Users ({{ $users->total() }})</h6>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">← Back to Dashboard</a>
            </div>
            <div class="card-body">
                <!-- FILTER & SEARCH FORM -->
                <form method="GET" action="{{ route('admin.users-management') }}" class="mb-4">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="form-control" placeholder="Search name, email, designation...">
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        <!-- Email Verified Filter -->
                        <div class="col-md-2">
                            <select name="email_verified" class="form-select">
                                <option value="">Any Email Status</option>
                                <option value="verified" {{ request('email_verified') === 'verified' ? 'selected' : '' }}>Email Verified</option>
                                <option value="unverified" {{ request('email_verified') === 'unverified' ? 'selected' : '' }}>Email Not Verified</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-2">
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="From">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="To">
                        </div>

                        <!-- Actions -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Sort Indicators & Reset -->
<!-- Sort Indicators & Reset -->
<div class="d-flex justify-content-between mb-3">
    @php
        $filters = request()->except(['_token', '_method', 'page', 'sort', 'order']);
        $hasActiveFilters = collect($filters)->filter()->isNotEmpty();
    @endphp

    @if($hasActiveFilters)
        <a href="{{ route('admin.users-management') }}" class="btn btn-outline-secondary btn-sm">↺ Clear Filters</a>
    @else
        <div></div>
    @endif

    <small class="text-muted">
        Sorted by: {{ ucfirst(str_replace('_', ' ', request('sort', 'created_at'))) }}
        ({{ strtoupper(request('order', 'desc')) }})
    </small>
</div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
<thead>
<tr>
    <th>Sr.No</th>
    <th>
        @php
            $sortField = 'full_name';
            $currentSort = request('sort', 'created_at');
            $currentOrder = request('order', 'desc');
        @endphp
        <a href="{{ request()->fullUrlWithQuery(['sort' => $sortField, 'order' => ($currentSort === $sortField && $currentOrder === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">
            User 
            @if($currentSort === $sortField)
                @if($currentOrder === 'asc')
                    <i class="fas fa-sort-up text-primary"></i>
                @else
                    <i class="fas fa-sort-down text-primary"></i>
                @endif
            @else
                <i class="fas fa-sort text-muted"></i>
            @endif
        </a>
    </th>
    <th>
        @php
            $sortField = 'email';
            $currentSort = request('sort', 'created_at');
            $currentOrder = request('order', 'desc');
        @endphp
        <a href="{{ request()->fullUrlWithQuery(['sort' => $sortField, 'order' => ($currentSort === $sortField && $currentOrder === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">
            Email 
            @if($currentSort === $sortField)
                @if($currentOrder === 'asc')
                    <i class="fas fa-sort-up text-primary"></i>
                @else
                    <i class="fas fa-sort-down text-primary"></i>
                @endif
            @else
                <i class="fas fa-sort text-muted"></i>
            @endif
        </a>
    </th>
    <th>Role</th>
    <th>
        @php
            $sortField = 'status';
            $currentSort = request('sort', 'created_at');
            $currentOrder = request('order', 'desc');
        @endphp
        <a href="{{ request()->fullUrlWithQuery(['sort' => $sortField, 'order' => ($currentSort === $sortField && $currentOrder === 'asc') ? 'desc' : 'asc']) }}" class="text-decoration-none">
            Status 
            @if($currentSort === $sortField)
                @if($currentOrder === 'asc')
                    <i class="fas fa-sort-up text-primary"></i>
                @else
                    <i class="fas fa-sort-down text-primary"></i>
                @endif
            @else
                <i class="fas fa-sort text-muted"></i>
            @endif
        </a>
    </th>
    <th>Actions</th>
</tr>
</thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td>
                                        <strong>{{ $user->full_name }}</strong><br>
                                        <small class="text-muted">{{ $user->designation ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->role === 'superadmin' ? 'bg-danger' : ($user->role === 'admin' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->status === 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($user->status === 'pending')
                                            @if($user->email_verified_at)
                                                <span class="badge bg-warning">Pending Approval</span>
                                            @else
                                                <span class="badge bg-secondary">Email Not Verified</span>
                                            @endif
                                        @elseif($user->status === 'suspended')
                                            <span class="badge bg-danger">Suspended</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($user->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role === 'user')
                                            @if($user->status === 'pending' && $user->email_verified_at)
                                                <button type="button" class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal" 
                                                    data-user-id="{{ $user->id }}">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm mt-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal" 
                                                    data-user-id="{{ $user->id }}">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            @elseif($user->status === 'rejected' && $user->email_verified_at)
                                                <button type="button" class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#reapproveModal" 
                                                    data-user-id="{{ $user->id }}">
                                                    <i class="fas fa-check"></i> Re-Approve
                                                </button>
                                            @elseif($user->status === 'verified')
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#suspendModal" 
                                                    data-user-id="{{ $user->id }}">
                                                    <i class="fas fa-user-slash"></i> Suspend
                                                </button>
                                            @elseif($user->status === 'suspended')
                                                <button type="button" class="btn btn-outline-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#activateModal" 
                                                    data-user-id="{{ $user->id }}">
                                                    <i class="fas fa-user-check"></i> Activate
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-info btn-sm mt-1">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
    <div class="col-12">
        <div class="card shadow">
            
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">All Users ({{ $users->total() }})</h6>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">← Back to Dashboard</a>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Sr.N</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    <strong>{{ $user->full_name }}</strong><br>
                                    <small class="text-muted">{{ $user->designation ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'superadmin' ? 'bg-danger' : ($user->role === 'admin' ? 'bg-warning' : 'bg-secondary') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->status === 'verified')
                                        <span class="badge bg-success">Verified</span>
                                    @elseif($user->status === 'pending')
                                        @if($user->email_verified_at)
                                            <span class="badge bg-warning">Pending Approval</span>
                                        @else
                                            <span class="badge bg-secondary">Email Not Verified</span>
                                        @endif
                                    @elseif($user->status === 'suspended')
                                        <span class="badge bg-danger">Suspended</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($user->status) }}</span>
                                    @endif
                                </td>
        <td>
            @if($user->role === 'user')
                @if($user->status === 'pending' && $user->email_verified_at)
                    <!-- Pending + Verified → Approve / Reject -->
                    <button type="button" class="btn btn-success btn-sm" onclick="approveUser({{ $user->id }})">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger btn-sm mt-1" 
                        data-bs-toggle="modal" 
                        data-bs-target="#rejectModal" 
                        data-user-id="{{ $user->id }}">
                        <i class="fas fa-times"></i> Reject
                    </button>
                @elseif($user->status === 'rejected' && $user->email_verified_at)
                    <button type="button" class="btn btn-success btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#reapproveModal" 
                        data-user-id="{{ $user->id }}">
                        <i class="fas fa-check"></i> Re-Approve
                    </button>
                @elseif($user->status === 'verified')
                    <!-- Verified → Suspend -->
                    <form action="{{ route('admin.user.suspend', $user->id) }}" method="1POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="reason" value="Admin suspended">
                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Suspend">
                            <i class="fas fa-user-slash"></i> Suspend
                        </button>
                    </form>
                @elseif($user->status === 'suspended')
                    <!-- Suspended → Activate -->
                    <form action="{{ route('admin.user.activate', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm" title="Activate">
                            <i class="fas fa-user-check"></i> Activate
                        </button>
                    </form>
                @endif

                <!-- Always show View -->
                <a href="{{ route('admin.user.details', $user->id) }}" class="btn btn-info btn-sm mt-1">
                    <i class="fas fa-eye"></i> View
                </a>
            @endif
        </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Approve User Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Approve User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to approve this user?</p>
                    <div class="mb-3">
                        <label class="form-label">Reason (Optional)</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="e.g., Documents verified..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Reject Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to reject this user?</p>
                    <div class="mb-3">
                        <label class="form-label">Reason (Optional)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Re-Approve Reason Modal -->
<div class="modal fade" id="reapproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Re-Approve User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reapproveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to re-approve this user?</p>
                    <div class="mb-3">
                        <label class="form-label">Reason (Optional)</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="e.g., Documents resubmitted..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Re-Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Suspend User Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Suspend User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="suspendForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p>Select a reason for suspension:</p>

                    <!-- Predefined Reasons (Dropdown) -->
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <select name="predefined_reason" id="predefinedReason" class="form-select" required>
                            <option value="">-- Select a reason --</option>
                            <option value="inappropriate_content">Inappropriate Content</option>
                            <option value="spam_or_scam">Spam or Scam Activity</option>
                            <option value="fake_profile">Fake or Incomplete Profile</option>
                            <option value="policy_violation">Violation of Platform Policies</option>
                            <option value="payment_fraud">Payment or Financial Fraud</option>
                            <option value="abusive_behavior">Abusive or Harassing Behavior</option>
                            <option value="other">Other (Specify Below)</option>
                        </select>
                    </div>

                    <!-- Custom Reason (Appears only if "Other" is selected) -->
                    <div class="mb-3" id="customReasonGroup" style="display: none;">
                        <label class="form-label">Specify Reason <span class="text-danger">*</span></label>
                        <textarea name="custom_reason" class="form-control" rows="2" placeholder="Please provide details..."></textarea>
                        <div class="form-text">Required when "Other" is selected.</div>
                    </div>

                    <!-- Hidden final reason field (sent to server) -->
                    <input type="hidden" name="reason" id="finalReason" required>

                    <!-- Suspension End Date -->
                    <div class="mb-3">
                        <label class="form-label">Suspension End Date <span class="text-danger">*</span></label>
                        <input type="date" name="suspended_until" class="form-control" 
                               min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                        <small class="text-muted">User will be suspended until this date (inclusive).</small>
                    </div>

                    <!-- Optional Evidence Upload -->
                    <div class="mb-3">
                        <label class="form-label">Supporting Evidence (Optional)</label>
                        <input type="file" name="evidence_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.xlsx,.xls,.doc,.docx">
                        <div class="form-text text-muted">
                            Upload screenshots, logs, or documents (Max: 10MB). Accepted: JPG, PNG, PDF, Excel, Word.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="suspendSubmitBtn">Suspend User</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Activate User Modal -->
<div class="modal fade" id="activateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Activate User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="activateForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to activate this user?</p>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="e.g., Suspension period completed..." requird></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Activate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Set approve form action when modal opens
document.getElementById('approveModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const userId = button.getAttribute('data-user-id');
    const form = document.getElementById('approveForm');
    form.action = "{{ route('admin.users.approve', ':id') }}".replace(':id', userId);
});
    // Set form action when modal opens
    document.getElementById('rejectModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const form = document.getElementById('rejectForm');
        form.action = "{{ route('admin.users.reject', ':id') }}".replace(':id', userId);
    });
    // Setup re-approve modal
document.getElementById('reapproveModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const userId = button.getAttribute('data-user-id');
    const form = document.getElementById('reapproveForm');
    form.action = "{{ route('admin.users.approve', ':id') }}".replace(':id', userId);
});
let currentSuspendUserId = null;

// Set user ID when modal opens
document.getElementById('suspendModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    currentSuspendUserId = button.getAttribute('data-user-id');

    // Set default date
    const today = new Date();
    const defaultDate = new Date(today);
    defaultDate.setDate(today.getDate() + 7);
    document.getElementById('suspendDate').value = defaultDate.toISOString().split('T')[0];
});

// Handle suspend
function submitSuspend() {
    const reason = document.getElementById('suspendReason').value;
    const date = document.getElementById('suspendDate').value;

    if (!reason || !date) {
        alert('Please fill all fields.');
        return;
    }

    const url = "{{ route('admin.user.suspend', ':id') }}".replace(':id', currentSuspendUserId);

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            reason: reason,
            suspended_until: date
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(err => {
        alert('Error: ' + err.message);
    });
}

// Show/hide custom reason field
document.getElementById('predefinedReason').addEventListener('change', function() {
    const customGroup = document.getElementById('customReasonGroup');
    const customReason = document.querySelector('[name="custom_reason"]');
    const finalReason = document.getElementById('finalReason');
    
    if (this.value === 'other') {
        customGroup.style.display = 'block';
        customReason.setAttribute('required', 'required');
        finalReason.removeAttribute('required'); // Will be filled on submit
    } else {
        customGroup.style.display = 'none';
        customReason.removeAttribute('required');
        // Set final reason immediately
        document.getElementById('finalReason').value = this.options[this.selectedIndex].text;
    }
});

// Handle form submission
document.getElementById('suspendForm').addEventListener('submit', function(e) {
    const predefined = document.getElementById('predefinedReason').value;
    const customReason = document.querySelector('[name="custom_reason"]').value;
    const finalReason = document.getElementById('finalReason');
    
    if (predefined === 'other') {
        if (!customReason.trim()) {
            e.preventDefault();
            alert('Please specify the reason.');
            return;
        }
        finalReason.value = customReason.trim();
    } else if (predefined) {
        finalReason.value = document.getElementById('predefinedReason').options[document.getElementById('predefinedReason').selectedIndex].text;
    } else {
        e.preventDefault();
        alert('Please select a reason.');
        return;
    }
    
    // Optional: Disable button to prevent double-submit
    document.getElementById('suspendSubmitBtn').disabled = true;
    document.getElementById('suspendSubmitBtn').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
});

// Set suspend form action when modal opens
document.getElementById('suspendModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const userId = button.getAttribute('data-user-id');
    const form = document.getElementById('suspendForm');
    form.action = "{{ route('admin.user.suspend', ':id') }}".replace(':id', userId);

    // Set default date: 7 days from today
    const today = new Date();
    const defaultDate = new Date(today);
    defaultDate.setDate(today.getDate() + 7);
    form.querySelector('[name="suspended_until"]').value = defaultDate.toISOString().split('T')[0];
});
// Set activate form action when modal opens
document.getElementById('activateModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const userId = button.getAttribute('data-user-id');
    const form = document.getElementById('activateForm');
    form.action = "{{ route('admin.user.activate', ':id') }}".replace(':id', userId);
});
</script>
@endpush