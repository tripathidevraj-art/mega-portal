@extends('layouts.app')

@section('title', 'Manage Users')

@section('header', 'User Management')
@section('subheader', 'Approve, suspend, or review user accounts')

@section('content')
<div class="row">
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

                    <div class="d-flex justify-content-end mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
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
@endsection

@push('scripts')
<script>
    // Approve user with confirmation
    function approveUser(userId) {
        if (confirm('Are you sure you want to approve this user?')) {
            fetch("{{ route('admin.users.approve', ':id') }}".replace(':id', userId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || 'User approved successfully!');
                location.reload();
            })
            .catch(err => {
                alert('Error: ' + (err.message || 'Something went wrong.'));
            });
        }
    }

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
</script>
@endpush