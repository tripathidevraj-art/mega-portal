@extends('layouts.app')

@section('title', 'Manage Admins')

@section('header', 'Admin Management')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Administrators List</h6>
        <a href="{{ route('superadmin.admins.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Add New Admin
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{ $admin->full_name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <span class="badge {{ $admin->role == 'superadmin' ? 'bg-danger' : 'bg-primary' }}">
                                    {{ ucfirst($admin->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $admin->status == 'verified' ? 'success' : 'warning' }}">
                                    {{ ucfirst($admin->status) }}
                                </span>
                            </td>
                            <td>{{ $admin->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($admin->id !== auth()->id())
                                    <button onclick="confirmDelete({{ $admin->id }})" 
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <span class="text-muted">Current User</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this admin?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/superadmin/admins/${id}`;
        form.submit();
    }
}
</script>
@endpush
@endsection