@extends('layouts.app')

@section('title', 'My Profile')

@section('header', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-body text-center">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" 
                    alt="Profile" class="rounded-circle mb-3" width="150" height="150">
            @else
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                    style="width: 150px; height: 150px;">
                    <i class="fas fa-user text-white fa-4x"></i>
                </div>
            @endif
                
                <h4>{{ $user->full_name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="mb-3">
                    <span class="badge bg-{{ $user->status == 'verified' ? 'success' : ($user->status == 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-edit me-1"></i> Edit Profile
                </button>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0">Account Stats</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="mb-0">{{ $user->jobPostings->count() }}</h5>
                            <small class="text-muted">Total Jobs</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="mb-0">{{ $user->productOffers->count() }}</h5>
                            <small class="text-muted">Total Offers</small>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <p class="mb-1"><small>Member since</small></p>
                    <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="mb-0">Personal Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Full Name</label>
                        <p class="mb-0">{{ $user->full_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="mb-0">{{ $user->phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Date of Birth</label>
                        <p class="mb-0">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Gender</label>
                        <p class="mb-0">{{ ucfirst($user->gender) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Country</label>
                        <p class="mb-0">{{ $user->country }}</p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Current Address</label>
                    <p class="mb-0">{{ $user->current_address }}</p>
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="mb-0">Professional Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Occupation</label>
                        <p class="mb-0">{{ $user->occupation ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Company</label>
                        <p class="mb-0">{{ $user->company ?: 'N/A' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Skills</label>
                        <div class="d-flex flex-wrap gap-2">
                            @if($user->skills && is_array($user->skills))
                                @foreach($user->skills as $skill)
                                    <span class="badge bg-light text-dark">{{ $skill }}</span>
                                @endforeach
                            @else
                                <p class="mb-0">No skills added</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0">Recent Activity</h6>
            </div>
            <div class="card-body">
                @if($activityLogs->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($activityLogs as $log)
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="badge bg-{{ $log->action_type == 'approved' ? 'success' : ($log->action_type == 'rejected' ? 'danger' : 'info') }} me-2">
                                            {{ ucfirst(str_replace('_', ' ', $log->action_type)) }}
                                        </span>
                                        <small>{{ $log->reason }}</small>
                                    </div>
                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No recent activity</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProfileForm" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="full_name" value="{{ $user->full_name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" class="form-control" name="phone" value="{{ $user->phone }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Occupation</label>
                            <input type="text" class="form-control" name="occupation" value="{{ $user->occupation }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" name="company" value="{{ $user->company }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Skills</label>
                            <textarea class="form-control" name="skills" rows="2" placeholder="Enter skills separated by commas">{{ $user->skills ? implode(', ', (array)$user->skills) : '' }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" class="form-control" name="profile_image" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
$(document).ready(function() {
    $('#editProfileForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showToast('success', 'Profile updated successfully!');
                $('#editProfileModal').modal('hide');
                
                // Page reload after 1 second
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                showToast('error', xhr.responseJSON?.message || 'An error occurred');
            }
        });
    });
    
    function showToast(type, message) {
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        
        $('#toast-container').append(toast);
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        setTimeout(() => toast.remove(), 5000);
    }
});
    
    function showToast(type, message) {
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        
        $('#toast-container').append(toast);
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        setTimeout(() => toast.remove(), 5000);
    }
});
</script>
@endpush