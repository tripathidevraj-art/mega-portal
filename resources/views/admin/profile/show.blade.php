@extends('layouts.app')

@section('title', 'Admin Profile')

@section('header', 'My Profile')

@section('content')
<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                @if($user->profile_image)
                    <img src="{{ Storage::url($user->profile_image) }}" 
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
                    @if($user->role !== 'user')
                        <span class="badge bg-info ms-1">{{ ucfirst($user->role) }}</span>
                    @endif
                </div>
                
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-edit me-1"></i> Edit Profile
                </button>
            </div>
        </div>

        <!-- Stats Card -->
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

    <!-- Info Sections -->
    <div class="col-md-8">
        <!-- Personal Info -->
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
                        <p class="mb-0">
                            {{ $user->phone_country_code }} {{ $user->phone }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">WhatsApp</label>
                        <p class="mb-0">
                            @if($user->whatsapp)
                                {{ $user->whatsapp_country_code }} {{ $user->whatsapp }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Date of Birth</label>
                        <p class="mb-0">
                            {{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Gender</label>
                        <p class="mb-0">{{ ucfirst($user->gender) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Info -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="mb-0">Address Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">Country</label>
                        <p class="mb-0">{{ $user->country }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">State</label>
                        <p class="mb-0">{{ $user->state }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">City</label>
                        <p class="mb-0">{{ $user->city }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">ZIP Code</label>
                        <p class="mb-0">{{ $user->zip_code }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Current Address</label>
                        <p class="mb-0">{{ $user->current_address }}</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Communication Address</label>
                        <p class="mb-0">
                            {{ $user->communication_address ?: 'Same as current address' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Info -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="mb-0">Professional Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Designation</label>
                        <p class="mb-0">{{ $user->designation ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Company</label>
                        <p class="mb-0">{{ $user->company_name ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Industry Experience</label>
                        <p class="mb-0">{{ $user->industry_experience ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Volunteer Interests</label>
                        <p class="mb-0">{{ $user->volunteer_interests ?: 'N/A' }}</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Additional Info</label>
                    <p class="mb-0">{{ $user->additional_info ?: 'None' }}</p>
                </div>
            </div>
        </div>

        <!-- Identity Info -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="mb-0">Identity Documents</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Civil ID</label>
                        <p class="mb-0">{{ $user->civil_id ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Passport Number</label>
                        <p class="mb-0">{{ $user->passport_number ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Passport Expiry</label>
                        <p class="mb-0">
                            {{ $user->passport_expiry ? $user->passport_expiry->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Residency Type</label>
                        <p class="mb-0">{{ $user->residency_type ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Residency Expiry</label>
                        <p class="mb-0">
                            {{ $user->residency_expiry ? $user->residency_expiry->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Civil ID File</label>
                        <p class="mb-0">
                            @if($user->civil_id_file_path)
                                <a href="{{ Storage::url($user->civil_id_file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file me-1"></i> View Document
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

<!-- Recent Actions Table -->
<div class="card shadow">
    <div class="card-header">
        <h6 class="mb-0">Recent Actions</h6>
    </div>
    <div class="card-body">
        @if($activityLogs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Reason</th>
                            <th>Admin</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activityLogs as $log)
                            <tr>
                                <td>
                                    @if($log->targetUser)
                                        {{ $log->targetUser->full_name }}
                                        <br><small class="text-muted">{{ $log->targetUser->email }}</small>
                                    @else
                                        <em>Unknown User</em>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $log->actionBadgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $log->reason ?: '—' }}
                                </td>
                                <td>
                                    @if($log->admin)
                                        {{ $log->admin->full_name }}
                                        <br><small class="text-muted">{{ $log->admin->email }}</small>
                                    @else
                                        <em>Unknown Admin</em>
                                    @endif
                                </td>
                                <td>
                                    {{ $log->created_at->format('M d, Y') }}<br>
                                    <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted mb-0">No recent actions</p>
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
            <form id="editProfileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
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
                            <label class="form-label">Designation</label>
                            <input type="text" class="form-control" name="designation" value="{{ $user->designation }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" name="company_name" value="{{ $user->company_name }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Industry Experience</label>
                            <select class="form-select" name="industry_experience">
                                <option value="">Select</option>
                                <option value="0-3" {{ $user->industry_experience == '0-3' ? 'selected' : '' }}>0-3 years</option>
                                <option value="4-6" {{ $user->industry_experience == '4-6' ? 'selected' : '' }}>4-6 years</option>
                                <option value="7-10" {{ $user->industry_experience == '7-10' ? 'selected' : '' }}>7-10 years</option>
                                <option value="10+" {{ $user->industry_experience == '10+' ? 'selected' : '' }}>10+ years</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Volunteer Interests</label>
                            <textarea class="form-control" name="volunteer_interests" rows="2">{{ $user->volunteer_interests }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Additional Info</label>
                            <textarea class="form-control" name="additional_info" rows="2">{{ $user->additional_info }}</textarea>
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
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                showToast('error', xhr.responseJSON?.message || 'An error occurred while updating profile.');
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
</script>
@endpush