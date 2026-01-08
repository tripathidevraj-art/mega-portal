@extends('layouts.app')

@section('title', 'My Profile')

@section('header', 'My Profile')

@section('content')
<div class="row">
    <!-- Left Sidebar -->
    <div class="col-md-4">
        <!-- Profile Card -->
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
                    <span class="badge bg-{{ 
                        $user->status == 'verified' ? 'success' : 
                        ($user->status == 'pending' ? 'warning' : 
                        ($user->status == 'suspended' ? 'danger' : 'secondary')) 
                    }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                <button class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editProfileImageModal">
                    <i class="fas fa-camera me-1"></i> Change Photo
                </button>
            </div>
        </div>
<!-- Referral Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5><i class="fas fa-share-alt me-2"></i> Invite Friends & Earn Points</h5>
    </div>
    <div class="card-body">
        <p class="mb-3">Share your unique link to earn points:</p>
        
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="inviteLink" 
                   value="{{ url('/register?ref=' . Auth::user()->referral_code->code) }}" readonly>
            <button class="btn btn-primary" type="button" onclick="copyInviteLink()">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>

        <!-- Referral QR Button -->
        <button type="button" class="btn btn-outline-secondary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#qrModal">
            <i class="fas fa-qrcode me-1"></i> Show My QR Code
        </button>

        
        <a href="{{ route('user.referral.downline') }}" class="btn btn-outline-primary w-100 mb-3">
            <i class="fas fa-sitemap me-1"></i> My Downline
        </a>
        
        <!-- Enhanced Invite Buttons -->
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-success" onclick="openInviteModal('whatsapp')">
                <i class="fab fa-whatsapp me-2"></i> Invite via WhatsApp
            </button>
            <button type="button" class="btn btn-primary" onclick="openInviteModal('email')">
                <i class="fas fa-envelope me-2"></i> Invite via Email
            </button>
        </div>

        <!-- Stats Buttons -->
        <div class="d-flex justify-content-center gap-3 mt-3">
            <a href="{{ route('referral.invites') }}" class="btn btn-sm btn-outline-info">
                Invited: <span class="badge bg-info text-white">{{ Auth::user()->sentInvites()->count() }}</span>
            </a>
            <a href="{{ route('referral.invites') }}" class="btn btn-sm btn-outline-success">
                Accepted: <span class="badge bg-success text-white">{{ Auth::user()->sentInvites()->where('accepted', true)->count() }}</span>
            </a>
        </div>
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
                            <small class="text-muted">Jobs Posted</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="mb-0">{{ $user->productOffers->count() }}</h5>
                            <small class="text-muted">Offers Made</small>
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

    <!-- Right Content -->
    <div class="col-md-8">
        <!-- Personal Info -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Personal Information</h6>
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPersonalModal">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Full Name</label>
                        <p>{{ $user->full_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p>{{ $user->phone_country_code }} {{ $user->phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">WhatsApp</label>
                        <p>{{ $user->whatsapp ? ' ' . $user->whatsapp_country_code . ' ' . $user->whatsapp : 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Date of Birth</label>
                        <p>{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Gender</label>
                        <p>{{ ucfirst($user->gender ?? 'N/A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Address Details</h6>
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"><small class="text-muted">Country</small><p>{{ $user->country ?? 'N/A' }}</p></div>
                    <div class="col-md-4"><small class="text-muted">State</small><p>{{ $user->state ?? 'N/A' }}</p></div>
                    <div class="col-md-4"><small class="text-muted">City</small><p>{{ $user->city ?? 'N/A' }}</p></div>
                    <div class="col-md-4"><small class="text-muted">ZIP Code</small><p>{{ $user->zip_code ?? 'N/A' }}</p></div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Current Address</small>
                    <p>{{ $user->current_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <small class="text-muted">Communication Address</small>
                    <p>{{ $user->communication_address ?? 'Same as current' }}</p>
                </div>
            </div>
        </div>

        <!-- Professional -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Professional Information</h6>
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editProfessionalModal">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Designation</small>
                        <p>{{ $user->designation ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Company</small>
                        <p>{{ $user->company_name ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-12 mb-2">
                        <small class="text-muted">Industry Experience</small>
                        <p>{{ $user->industry_experience ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Identity & Residency</h6>
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editDocumentsModal">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Civil ID</small>
                        <p>{{ $user->civil_id ?? 'Not uploaded' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Passport</small>
                        <p>{{ $user->passport_number ?? 'Not uploaded' }}
                            @if($user->passport_expiry)
                                <br><small class="text-muted">Expires: {{ $user->passport_expiry->format('M/d/Y') }}</small>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Residency Type</small>
                        <p>{{ $user->residency_type ?? 'N/A' }}
                            @if($user->residency_expiry)
                                <br><small class="text-muted">Expires: {{ $user->residency_expiry->format('M/d/Y') }}</small>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Civil ID Document</small>
                        <p>
                            @if($user->civil_id_file_path)
                                <a href="{{ asset('storage/' . $user->civil_id_file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf me-1"></i> View
                                </a>
                            @else
                                Not uploaded
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

<!-- Volunteer & Additional -->
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Interests & Notes</h6>
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editInterestsModal">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <small class="text-muted">Volunteer Interests</small>
        <p>
            @php
                $raw = $user->volunteer_interests ?? '';

                if (empty($raw)) {
                    $result = '';
                } else {
                    // Step 1: Convert to string if array
                    if (is_array($raw)) {
                        $raw = implode(', ', $raw);
                    }

                    // Step 2: Remove ALL special characters EXCEPT letters, spaces, and commas
                    // This will destroy JSON, quotes, brackets, slashes — everything
                    $clean = preg_replace('/[^a-zA-Z0-9,\s]/', '', $raw);

                    // Step 3: Split by comma, trim, remove empty
                    $parts = array_filter(array_map('trim', explode(',', $clean)), fn($s) => !empty($s));

                    // Step 4: Remove any leftover numeric-only or very short garbage
                    $parts = array_filter($parts, fn($s) => strlen($s) > 2 && !is_numeric($s));

                    // Step 5: Re-index and join
                    $result = implode(', ', array_values($parts));
                }
            @endphp

            {{ $result ?: 'Not specified' }}
        </p>
        </div>
        <div>
            <small class="text-muted">Additional Information</small>
            <p>{{ $user->additional_info ?? 'None' }}</p>
        </div>
    </div>
</div>

        <!-- Activity Log -->
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
                                        <span class="badge bg-{{ 
                                            $log->action_type == 'profile_updated' ? 'info' :
                                            ($log->action_type == 'approved' ? 'success' : 'secondary')
                                        }} me-2">
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


<!-- ============= MODALS ============= -->

<div class="modal fade" id="editPersonalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Personal Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form class="profile-update-form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" name="full_name" value="{{ $user->full_name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Phone Code *</label>
                            <input type="text" class="form-control" name="phone_country_code" value="{{ $user->phone_country_code }}" required>
                        </div>
                        <div class="col-8 mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" class="form-control" name="phone" value="{{ $user->phone }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">WhatsApp Code</label>
                            <input type="text" class="form-control" name="whatsapp_country_code" value="{{ $user->whatsapp_country_code }}">
                        </div>
                        <div class="col-8 mb-3">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" class="form-control" name="whatsapp" value="{{ $user->whatsapp }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth" value="{{ $user->date_of_birth }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="">Select</option>
                                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
           <form class="profile-update-form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country *</label>
                            <input type="text" class="form-control" name="country" value="{{ $user->country }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State *</label>
                            <input type="text" class="form-control" name="state" value="{{ $user->state }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control" name="city" value="{{ $user->city }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ZIP Code *</label>
                            <input type="text" class="form-control" name="zip_code" value="{{ $user->zip_code }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Address *</label>
                        <textarea class="form-control" name="current_address" rows="2" required>{{ $user->current_address }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Communication Address (if different)</label>
                        <textarea class="form-control" name="communication_address" rows="2">{{ $user->communication_address }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editProfessionalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Professional Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form class="profile-update-form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" class="form-control" name="designation" value="{{ $user->designation }}">
                        <small class="text-muted">e.g., Software Engineer, Marketing Manager</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="company_name" value="{{ $user->company_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Industry Experience</label>
                        <textarea class="form-control" name="industry_experience" rows="2" placeholder="Describe your professional background, years of experience, etc.">{{ $user->industry_experience }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editDocumentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Identity & Residency Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form class="profile-update-form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Civil ID Number</label>
                            <input type="text" class="form-control" name="civil_id" value="{{ $user->civil_id }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Civil ID Document (PDF/Image)</label>
                            <input type="file" class="form-control" name="civil_id_file" accept=".pdf,.jpg,.jpeg,.png">
                            @if($user->civil_id_file_path)
                                <small class="form-text">
                                    <a href="{{ asset('storage/' . $user->civil_id_file_path) }}" target="_blank">Current file</a>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passport Number</label>
                            <input type="text" class="form-control" name="passport_number" value="{{ $user->passport_number }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passport Expiry Date</label>
                            <input type="date" class="form-control" name="passport_expiry" value="{{ $user->passport_expiry ? $user->passport_expiry->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Residency Type</label>
                            <input type="text" class="form-control" name="residency_type" value="{{ $user->residency_type }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Residency Expiry Date</label>
                            <input type="date" class="form-control" name="residency_expiry" value="{{ $user->residency_expiry ? $user->residency_expiry->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Documents</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Interests Modal -->
<div class="modal fade" id="editInterestsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Volunteer Interests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form class="profile-update-form" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Select your interests:</p>
                    <div id="profileInterestsList" class="mb-3">
                        @php
                            $allInterests = [
                                'Teaching', 'Healthcare', 'Environment', 'Animal Care',
                                'Arts', 'Sports', 'Technology', 'Disaster Relief',
                                'Elderly Care', 'Youth Mentorship', 'Fundraising', 'Translation'
                            ];
                            // Ensure it's an array
                            $selectedInterests = is_array($user->volunteer_interests)
                                ? $user->volunteer_interests
                                : (is_string($user->volunteer_interests)
                                    ? array_filter(array_map('trim', explode(',', $user->volunteer_interests)))
                                    : []);
                        @endphp

                        @foreach($allInterests as $interest)
                            <span 
                                class="interest-badge d-inline-flex align-items-center px-2 py-1 me-2 mb-2 rounded-pill"
                                data-interest="{{ $interest }}"
                                style="border: 1px solid #cbd5e1; cursor: pointer; min-width: 80px;"
                            >
                                {{ $interest }}
                                @if(in_array($interest, $selectedInterests))
                                    <i class="fas fa-check ms-2" style="font-size:0.8em;"></i>
                                @else
                                    <i class="fas fa-plus ms-2" style="font-size:0.8em;"></i>
                                @endif
                            </span>
                        @endforeach
                    </div>

                    <!-- Hidden input to send FINAL comma-separated string -->
                    <input type="hidden" name="volunteer_interests" id="profile_volunteer_interests" value="{{ $user->volunteer_interests ? implode(', ', $user->volunteer_interests) : '' }}">

                    <div class="mb-3">
                        <label class="form-label">Additional Information</label>
                        <textarea class="form-control" name="additional_info" rows="3" placeholder="Any other details you'd like to share?">{{ $user->additional_info }}</textarea>
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
<div class="modal fade" id="editProfileImageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
           <form class="profile-update-form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" 
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                    @else
                        <div class="bg-light border rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-user text-secondary fa-3x"></i>
                        </div>
                    @endif

                    <div class="mt-3">
                        <label class="form-label d-block">Upload New Photo</label>
                        <input type="file" class="form-control" name="profile_image" accept="image/*" required>
                        <small class="form-text text-muted">JPG, PNG up to 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Photo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
       <style>
        .toast.bg-error {
        background-color: #ef4444 !important;
        color: white !important;
        }
        </style>
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="inviteForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="inviteModalLabel">Invite Friend</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="inviteType" name="type">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" id="contactLabel">WhatsApp Number *</label>
                        <input type="text" class="form-control" name="contact" required placeholder="919876543210">
                        <small class="form-text text-muted" id="contactHelp">Include country code (e.g., 91 for India)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Invite</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Your Referral QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-3">Scan to register instantly</p>
                
                <!-- QR Placeholder -->
                <div id="qrContainer" class="d-inline-block">
                    <div class="text-muted">Generating QR...</div>
                </div>

                <p class="mt-3 small text-muted">
                    Code: <strong>{{ Auth::user()->referral_code->code }}</strong>
                </p>
            </div>
<div class="modal-footer justify-content-center">
    <button class="btn btn-sm btn-outline-primary" onclick="downloadQR()">
        <i class="fas fa-download me-1"></i> Download PNG
    </button>
    {{-- <button class="btn btn-sm btn-success" onclick="openInviteVia('whatsapp')">
        <i class="fab fa-whatsapp me-1"></i> WhatsApp
    </button>
    <button class="btn btn-sm btn-primary" onclick="openInviteVia('email')">
        <i class="fas fa-envelope me-1"></i> Email
    </button> --}}
</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.profile-update-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('user.profile.update') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showToast('success', response.message);
                form.closest('.modal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                let msg = 'An error occurred';
                if (xhr.responseJSON?.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                }
                showToast('error', msg);
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
        const bsToast = new bootstrap.Toast(toast[0], { delay: 3000 });
        bsToast.show();
        setTimeout(() => toast.remove(), 3500);
    }
});
// Volunteer Interests: Toggle selection and update hidden field
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editInterestsModal');
    if (!modal) return;

    // Get initial value from hidden input (comma-separated string)
    const hiddenInput = document.getElementById('profile_volunteer_interests');
    let selected = new Set();
    if (hiddenInput.value) {
        selected = new Set(
            hiddenInput.value
                .split(',')
                .map(s => s.trim())
                .filter(s => s)
        );
    }

    // Update badge visuals
    function updateBadges() {
        modal.querySelectorAll('.interest-badge').forEach(badge => {
            const interest = badge.dataset.interest;
            const icon = badge.querySelector('i');
            if (selected.has(interest)) {
                badge.classList.remove('bg-light');
                badge.classList.add('bg-primary', 'text-white');
                icon.className = 'fas fa-check ms-2';
            } else {
                badge.classList.remove('bg-primary', 'text-white');
                badge.classList.add('bg-light');
                icon.className = 'fas fa-plus ms-2';
            }
        });
    }

    // Click handler
    modal.querySelectorAll('.interest-badge').forEach(badge => {
        badge.addEventListener('click', function() {
            const interest = this.dataset.interest;
            if (selected.has(interest)) {
                selected.delete(interest);
            } else {
                selected.add(interest);
            }
            // Update hidden input with COMMA-SEPARATED STRING (to match DB format)
            hiddenInput.value = Array.from(selected).join(', ');
            updateBadges();
        });
    });

    // Initialize visuals
    updateBadges();
});
</script>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function openInviteModal(type) {
    const modal = new bootstrap.Modal(document.getElementById('inviteModal'));
    document.getElementById('inviteType').value = type;
    
    const label = document.getElementById('contactLabel');
    const help = document.getElementById('contactHelp');
    
    if (type === 'whatsapp') {
        label.textContent = 'WhatsApp Number *';
        help.textContent = 'Include country code (e.g., 91 for India)';
        document.querySelector('[name="contact"]').placeholder = '919876543210';
    } else {
        label.textContent = 'Email Address *';
        help.textContent = 'Valid email required';
        document.querySelector('[name="contact"]').placeholder = 'friend@example.com';
    }
    
    modal.show();
}

document.getElementById('inviteForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
try {
const response = await fetch("{{ route('referral.invite') }}", {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    },
    body: formData
}); 

const result = await response.json();
if (!response.ok) {
    let msg = 'Failed to send invite.';
    if (result.error) {
        msg = result.error; // ✅ Use 'error' key, not 'message'
    } else if (result.errors) {
        msg = Object.values(result.errors)[0][0];
    }
    showToast('error', msg);
    return;
}

    if (result.redirect) {
        window.open(result.redirect, '_blank');
    }
    showToast('success', result.message || 'Invite sent!');
    bootstrap.Modal.getInstance(document.getElementById('inviteModal')).hide();
    e.target.reset();

} catch (error) {
    console.error('Network error:', error);
    showToast('error', 'Network error. Please try again.');
}
});


function generateQR() {
    const qrContainer = document.getElementById('qrContainer');
    qrContainer.innerHTML = '';

    const url = "{{ url('/register?ref=' . Auth::user()->referral_code->code) }}";
    
    // Create a div for QR
    const qrDiv = document.createElement('div');
    qrDiv.id = 'qrcode';
    qrDiv.style.margin = '0 auto';
    qrContainer.appendChild(qrDiv);

    // Generate QR (uses canvas by default, but we'll style it)
    new QRCode(qrDiv, {
        text: url,
        width: 200,
        height: 200,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.M
    });
}

function downloadQR() {
    const canvas = document.querySelector('#qrcode canvas');
    if (!canvas) {
        alert('QR not ready yet. Please wait.');
        return;
    }

    const link = document.createElement('a');
    link.download = 'referral-{{ Str::slug(Auth::user()->full_name, '') }}-{{ Auth::user()->referral_code->code }}RN.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

// Generate QR when modal opens
document.getElementById('qrModal').addEventListener('show.bs.modal', function () {
    setTimeout(() => {
        generateQR();
    }, 100); // Small delay to ensure DOM is ready
});

function copyInviteLink() {
    const link = document.getElementById('inviteLink');
    link.select();
    document.execCommand('copy');
    alert('Invite link copied to clipboard!');
}
</script>
@endpush