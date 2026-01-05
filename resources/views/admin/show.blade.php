@extends('layouts.app')

@section('title', 'User Profile')

@section('header', 'User Profile')
@section('subheader', 'All user details — section-wise with inline editing')

@push('styles')
<style>
    .section-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        margin-bottom: 24px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .section-header {
        background: #474747ff;
        color: white;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 1.05rem;
    }
    .edit-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.15rem;
        cursor: pointer;
        opacity: 0.9;
        transition: all 0.2s;
    }
    .edit-btn:hover {
        opacity: 1;
        transform: scale(1.15);
    }
    .section-body {
        padding: 22px;
        background: #fafafa;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .info-label {
        font-weight: 600;
        color: #555;
        width: 45%;
    }
    .info-value {
        color: #2d3748;
        width: 55%;
        text-align: right;
        word-break: break-word;
    }
    .info-value.empty { color: #aaa; font-style: italic; }
    .modal-content {
        border-radius: 12px;
        overflow: hidden;
    }
    .modal-header {
        background: #8b0000;
        color: white;
        border-bottom: none;
    }
    .btn-update {
        background: #8b0000;
        border: none;
        padding: 10px 24px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .btn-update:hover {
        background: #7a0000;
    }
    .file-link {
        color: #8b0000;
        text-decoration: underline;
    }
    .file-link:hover {
        color: #b00000;
    }
    @media (max-width: 768px) {
        .info-label, .info-value {
            width: 100%;
            text-align: left;
        }
        .info-row {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">

                <!-- User Header -->
<div class="d-flex justify-content-between align-items-start mb-5 pb-3 border-bottom">
    <div class="d-flex align-items-start gap-4">
        <!-- Profile Image -->
<!-- Profile Image (Main Header - NO ID) -->
<div>
    @if($user->profile_image)
        <img src="{{ Storage::url($user->profile_image) }}?v={{ $user->updated_at?->timestamp }}"
             alt="{{ $user->full_name }}"
             class="rounded-circle border"
             width="70" height="70"
             style="object-fit: cover; border-color: #e2e8f0;">
    @else
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=8b0000&color=fff&size=70"
             alt="{{ $user->full_name }}"
             class="rounded-circle border"
             width="70" height="70"
             style="border-color: #e2e8f0;">
    @endif
</div>

                    <!-- Name & Badges -->
                    <div>
                        <div class="d-flex align-items-baseline gap-2">
                            <h2 class="mb-1">{{ $user->full_name }}</h2>
                            {{-- Edit icon next to name --}}
                            <button type="button" class="btn btn-sm p-0 text-muted" data-bs-toggle="modal" data-bs-target="#editHeaderModal">
                                <i class="fas fa-edit fs-6"></i>
                            </button>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <span class="badge bg-secondary me-2">{{ ucfirst($user->role) }}</span>
                            <span class="badge 
                                @if($user->status === 'verified') bg-success
                                @elseif($user->status === 'suspended') bg-danger
                                @elseif($user->status === 'active') bg-info
                                @else bg-warning @endif">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="{{ route('admin.users-management') }}" class="btn btn-outline-secondary">
                        ← Back to Users
                    </a>
                </div>
            </div>

                <!-- ROW 1 -->
                <div class="row">
                    <!-- Basic Info -->
                    <div class="col-md-6 col-lg-4">
                        <div class="section-card">
                            <div class="section-header">
                                BASIC INFO
                                <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editBasicModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div class="section-body">
                                <div class="info-row">
                                    <span class="info-label">Full Name</span>
                                    <span class="info-value">{{ $user->full_name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ $user->email }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Phone</span>
                                    <span class="info-value">{{ $user->phone_country_code }} {{ $user->phone }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">WhatsApp</span>
                                    <span class="info-value">
                                        @if($user->whatsapp)
                                            {{ $user->whatsapp_country_code }} {{ $user->whatsapp }}
                                        @else
                                            <span class="empty">Not provided</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Date of Birth</span>
                                    <span class="info-value">{{ $user->date_of_birth->format('d M Y') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Gender</span>
                                    <span class="info-value">{{ ucfirst($user->gender) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email Verified</span>
                                    <span class="info-value">
                                        @if($user->email_verified_at)
                                            <span class="text-success">Yes</span>
                                        @else
                                            <span class="text-warning">No</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Info -->
                    <div class="col-md-6 col-lg-4">
                        <div class="section-card">
                            <div class="section-header">
                                ADDRESS
                                <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div class="section-body">
                                <div class="info-row">
                                    <span class="info-label">Country</span>
                                    <span class="info-value">{{ $user->country }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">State</span>
                                    <span class="info-value">{{ $user->state }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">City</span>
                                    <span class="info-value">{{ $user->city }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">ZIP Code</span>
                                    <span class="info-value">{{ $user->zip_code }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Current Address</span>
                                    <span class="info-value">{{ $user->current_address }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Comm. Address</span>
                                    <span class="info-value">
                                        @if($user->communication_address)
                                            {{ $user->communication_address }}
                                        @else
                                            <span class="empty">Same as current</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Info -->
                    <div class="col-md-6 col-lg-4">
                        <div class="section-card">
                            <div class="section-header">
                                PROFESSIONAL
                                <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editProfessionalModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div class="section-body">
                                <div class="info-row">
                                    <span class="info-label">Designation</span>
                                    <span class="info-value">{{ $user->designation ?? '–' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Company</span>
                                    <span class="info-value">{{ $user->company_name ?? '–' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Experience</span>
                                    <span class="info-value">{{ $user->industry_experience ?? '–' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Volunteer Interests</span>
                                    <span class="info-value">
                                        @if($user->volunteer_interests)
                                            {{ $user->volunteer_interests }}
                                        @else
                                            <span class="empty">None</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Additional Info</span>
                                    <span class="info-value">
                                        @if($user->additional_info)
                                            {{ Str::limit($user->additional_info, 50) }}
                                        @else
                                            <span class="empty">–</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW 2 -->
                <div class="row mt-4">
                    <!-- Identity Documents -->
                    <div class="col-md-6 col-lg-6">
                        <div class="section-card">
                            <div class="section-header">
                                IDENTITY & DOCUMENTS
                                <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editIdentityModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div class="section-body">
                                <div class="info-row">
                                    <span class="info-label">Civil ID</span>
                                    <span class="info-value">{{ $user->civil_id ?? '–' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Civil ID File</span>
                                    <span class="info-value">
                                        @if($user->civil_id_file_path)
                                            <a href="{{ Storage::url($user->civil_id_file_path) }}" class="file-link" target="_blank">View Document</a>
                                        @else
                                            <span class="empty">Not uploaded</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Passport Number</span>
                                    <span class="info-value">{{ $user->passport_number ?? '–' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Passport Expiry</span>
                                    <span class="info-value">
                                        @if($user->passport_expiry)
                                            {{ $user->passport_expiry->format('d M Y') }}
                                        @else
                                            <span class="empty">–</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Residency Type</span>
                                    <span class="info-value">{{ $user->residency_type ?? '–' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Residency Expiry</span>
                                    <span class="info-value">
                                        @if($user->residency_expiry)
                                            {{ $user->residency_expiry->format('d M Y') }}
                                        @else
                                            <span class="empty">–</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Membership & System -->
                    <div class="col-md-6 col-lg-6">
                        <div class="section-card">
                            <div class="section-header">
                                MEMBERSHIP & SYSTEM
                                <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editMembershipModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div class="section-body">
                                <div class="info-row">
                                    <span class="info-label">User ID</span>
                                    <span class="info-value">#{{ $user->id }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Role</span>
                                    <span class="info-value">{{ ucfirst($user->role) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Status</span>
                                    <span class="info-value">{{ ucfirst($user->status) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Suspended Until</span>
                                    <span class="info-value">
                                        @if($user->suspended_until)
                                            {{ $user->suspended_until->format('d M Y H:i') }}
                                        @else
                                            <span class="empty">–</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Suspension Reason</span>
                                    <span class="info-value">
                                        @if($user->suspension_reason)
                                            {{ $user->suspension_reason }}
                                        @else
                                            <span class="empty">–</span>
                                        @endif
                                    </span>
                                </div>
                            @if($latestSuspensionLog && !empty($latestSuspensionLog->metadata['evidence_file']))
                                <div class="info-row">
                                    <label>Evidence:</label>
                                    <a href="{{ asset('storage/' . $latestSuspensionLog->metadata['evidence_file']) }}" 
                                    target="_blank" 
                                    class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-paperclip"></i> View Evidence
                                    </a>
                                </div>
                            @endif
                                <div class="info-row">
                                    <span class="info-label">Created At</span>
                                    <span class="info-value">{{ $user->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Last Updated</span>
                                    <span class="info-value">{{ $user->updated_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODALS (Basic, Address, Professional, Identity, Membership) -->
<!-- Edit Header (Name, Photo, Status) Modal -->
<div class="modal fade" id="editHeaderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="headerForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Profile Image Upload -->

<div class="mb-4 text-center">
    <div class="position-relative d-inline-block">
        <img id="profilePreview"
             src="{{ $user->profile_image ? Storage::url($user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&background=8b0000&color=fff&size=80' }}" 
             alt="Preview" 
             class="rounded-circle border mb-2" 
             width="80" height="80" 
             style="object-fit: cover; border-color: #e2e8f0;">
        <label for="profile_image" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1" style="cursor: pointer;">
            <i class="fas fa-camera fa-xs"></i>
        </label>
        <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*">
    </div>
    <small class="form-text text-muted">Click camera icon to change photo</small>
</div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" value="{{ $user->full_name }}" required>
                    </div>

                    <!-- Role (Read-only for non-superadmin) -->
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" {{ auth()->user()->isSuperAdmin() ? '' : 'disabled' }}>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @unless(auth()->user()->isSuperAdmin())
                            <small class="form-text text-muted">Role can only be changed by Super Admin</small>
                        @endunless
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $user->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ $user->status == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <!-- Suspension (conditionally shown via JS or always present) -->
                    <div class="mb-3">
                        <label class="form-label">Suspension Reason</label>
                        <textarea name="suspension_reason" class="form-control" rows="2">{{ $user->suspension_reason }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Suspended Until</label>
                        <input type="datetime-local" name="suspended_until" class="form-control" 
                               value="{{ $user->suspended_until ? $user->suspended_until->format('Y-m-d\\TH:i') : '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-update">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Basic Info Modal -->
<div class="modal fade" id="editBasicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Basic Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="basicInfoForm" method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" value="{{ $user->full_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Country Code *</label>
                        <input type="text" name="phone_country_code" class="form-control" value="{{ $user->phone_country_code }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone *</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">WhatsApp Country Code</label>
                        <input type="text" name="whatsapp_country_code" class="form-control" value="{{ $user->whatsapp_country_code }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control" value="{{ $user->whatsapp }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth *</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ $user->date_of_birth->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender *</label>
                        <select name="gender" class="form-select" required>
                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addressForm" method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Country *</label>
                        <input type="text" name="country" class="form-control" value="{{ $user->country }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">State *</label>
                        <input type="text" name="state" class="form-control" value="{{ $user->state }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City *</label>
                        <input type="text" name="city" class="form-control" value="{{ $user->city }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ZIP Code *</label>
                        <input type="text" name="zip_code" class="form-control" value="{{ $user->zip_code }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Address *</label>
                        <textarea name="current_address" class="form-control" rows="2" required>{{ $user->current_address }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Communication Address</label>
                        <textarea name="communication_address" class="form-control" rows="2">{{ $user->communication_address }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Professional Modal -->
<div class="modal fade" id="editProfessionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Professional Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="professionalForm" method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ $user->designation }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ $user->company_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Industry Experience</label>
                        <select name="industry_experience" class="form-select">
                            <option value="">Select</option>
                            <option value="0-3" {{ $user->industry_experience == '0-3' ? 'selected' : '' }}>0–3 Years</option>
                            <option value="4-6" {{ $user->industry_experience == '4-6' ? 'selected' : '' }}>4–6 Years</option>
                            <option value="7-10" {{ $user->industry_experience == '7-10' ? 'selected' : '' }}>7–10 Years</option>
                            <option value="10+" {{ $user->industry_experience == '10+' ? 'selected' : '' }}>10+ Years</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Volunteer Interests</label>
                        <textarea name="volunteer_interests" class="form-control" rows="2">{{ $user->volunteer_interests }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional Info</label>
                        <textarea name="additional_info" class="form-control" rows="3">{{ $user->additional_info }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Identity Modal -->
<div class="modal fade" id="editIdentityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Identity & Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="identityForm" method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Civil ID</label>
                        <input type="text" name="civil_id" class="form-control" value="{{ $user->civil_id }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Passport Number</label>
                        <input type="text" name="passport_number" class="form-control" value="{{ $user->passport_number }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Passport Expiry</label>
                        <input type="date" name="passport_expiry" class="form-control" value="{{ $user->passport_expiry ? $user->passport_expiry->format('Y-m-d') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Residency Type</label>
                        <select name="residency_type" class="form-select">
                            <option value="">Select</option>
                            <option value="Visit Visa" {{ $user->residency_type == 'Visit Visa' ? 'selected' : '' }}>Visit Visa</option>
                            <option value="Work Visa" {{ $user->residency_type == 'Work Visa' ? 'selected' : '' }}>Work Visa</option>
                            <option value="Residence Visa" {{ $user->residency_type == 'Residence Visa' ? 'selected' : '' }}>Residence Visa</option>
                            <option value="Permanent Residence" {{ $user->residency_type == 'Permanent Residence' ? 'selected' : '' }}>Permanent Residence</option>
                            <option value="Citizen" {{ $user->residency_type == 'Citizen' ? 'selected' : '' }}>Citizen</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Residency Expiry</label>
                        <input type="date" name="residency_expiry" class="form-control" value="{{ $user->residency_expiry ? $user->residency_expiry->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Membership Modal -->
<div class="modal fade" id="editMembershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Membership & Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="membershipForm" method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" disabled>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        <small class="text-muted">Role can only be changed by Super Admin</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $user->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ $user->status == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Suspension Reason</label>
                        <textarea name="suspension_reason" class="form-control" rows="2">{{ $user->suspension_reason }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Suspended Until</label>
                        <input type="datetime-local" name="suspended_until" class="form-control" 
                            value="{{ $user->suspended_until ? $user->suspended_until->format('Y-m-d\\TH:i') : '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Utility: Show floating toast-like message
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.floating-toast').forEach(el => el.remove());

    const color = type === 'success' ? '#4ade80' : '#f87171';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    const toast = document.createElement('div');
    toast.className = 'floating-toast position-fixed';
    toast.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 1055;
        max-width: 400px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        padding: 16px 20px;
        border: 2px solid ${color};
        font-family: system-ui, -apple-system, sans-serif;
    `;

    toast.innerHTML = `
        <div class="d-flex align-items-start">
            <i class="fas ${icon} fa-lg mt-1" style="color: ${color}; width: 24px;"></i>
            <div class="ms-3">
                <p class="mb-0" style="font-size: 0.95rem; color: #1e293b;">${message}</p>
            </div>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto-hide after 4 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 300);
    }, 4000);
}

document.addEventListener('DOMContentLoaded', function () {
    const userId = "{{ $user->id }}";
    const formMap = {
        headerForm: 'header',
        basicInfoForm: 'basic',
        addressForm: 'address',
        professionalForm: 'professional',
        identityForm: 'identity',
        membershipForm: 'membership'
    };

    // Set form actions
    Object.keys(formMap).forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.action = `/admin/users/${userId}/update-section/${formMap[formId]}`;
        }
    });
// Profile image preview
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('profilePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result; // Set preview to selected image
        };
        reader.readAsDataURL(file);
    } else {
        // If no file, revert to default avatar
        preview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(document.querySelector('[name="full_name"]').value || 'User')}&background=8b0000&color=fff&size=80`;
    }
});
// Reset preview when modal is closed
document.getElementById('editHeaderModal').addEventListener('hidden.bs.modal', function () {
    const preview = document.getElementById('profilePreview');
    const currentImage = "{{ $user->profile_image ? Storage::url($user->profile_image) : '' }}";
    if (currentImage) {
        preview.src = currentImage + '?v=' + Date.now();
    } else {
        preview.src = `https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=8b0000&color=fff&size=80`;
    }
    document.getElementById('profile_image').value = ''; // Clear file input
}); 
    // Handle form submissions
    document.querySelectorAll('form[id$="Form"]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Saving...';
            submitBtn.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message, 'success');
                    // Optional: reload after 1s to show updated data
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(result.message || 'Update failed.', 'error');
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    });
});
</script>
@endpush
@endsection