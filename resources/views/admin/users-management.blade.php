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
                                <th>#</th>
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
                                        <span class="badge bg-warning">Pending Approval</span>
                                    @elseif($user->status === 'suspended')
                                        <span class="badge bg-danger">Suspended</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($user->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->role === 'user')
                                        @if($user->status === 'pending')
                                            
                                            <form action="{{ route('admin.user.approve', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.user.reject', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm mt-1" title="Reject">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        @elseif($user->status === 'verified')
                                            <form action="{{ route('admin.user.suspend', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="reason" value="Admin suspended">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Suspend">
                                                    <i class="fas fa-user-slash"></i> Suspend
                                                </button>
                                            </form>
                                        @elseif($user->status === 'suspended')
                                            <form action="{{ route('admin.user.activate', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm" title="Activate">
                                                    <i class="fas fa-user-check"></i> Activate
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <a href="{{ route('admin.user.details', $user->id) }}" class="btn btn-info btn-sm mt-1">
                                        <i class="fas fa-eye"></i> View
                                    </a>
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
@endsection