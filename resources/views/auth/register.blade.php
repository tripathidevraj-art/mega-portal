@extends('layouts.app')

@section('title', 'Register')

@section('header', 'Create Account')
@section('subheader', 'Join our job and offer portal')

@push('styles')

<style>
    .section-header {
        cursor: pointer;
        transition: all 0.25s ease;
        user-select: none;
        border-radius: 8px !important;
    }
    .section-header:hover {
        background-color: #f1f5f9 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .accordion-body {
        background-color: #fafafa;
        border-radius: 0 0 8px 8px;
    }
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.4rem;
    }
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        padding: 0.6rem 0.9rem;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }
    .card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
    }
    .card-body {
        background: #ffffff;
    }
    .interest-badge {
        background: #edf2f7;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.875rem;
        margin: 4px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        border: 1px solid #e2e8f0;
    }
    .interest-badge:hover, .interest-badge.bg-primary {
        background: #4f46e5;
        color: white !important;
        border-color: #4f46e5;
    }
    .btn-success {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        border: none;
        padding: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .btn-success:hover {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.35);
    }
    .accordion-button::after {
        display: none !important;
    }
    .accordion-button:focus {
        box-shadow: none !important;
    }
    .text-muted {
        color: #718096 !important;
        font-size: 0.85rem;
    }
    .alert {
        border-radius: 10px;
    }

</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">

                {{-- Global Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <strong><i class="fas fa-exclamation-circle me-2"></i>Registration failed!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registrationForm" enctype="multipart/form-data">
                    @csrf

                    <!-- Tab Navigation -->
                    
            <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" type="button" role="tab">Personal</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="address-tab" type="button" role="tab">Address</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="professional-tab" type="button" role="tab">Professional</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="document-tab" type="button" role="tab">Document</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="volunteer-tab" type="button" role="tab">Volunteer</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="additional-tab" type="button" role="tab">Additional</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="account-tab" type="button" role="tab">Security</button>
                </li>
            </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="formTabContent">

                        <!-- Personal Information -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <div class="p-3 border rounded-3 mb-4">
                                {{-- Name + Email --}}
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-signature me-2 text-gray-500"></i> Full Name *</label>
                                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name') }}" required autofocus>
                                        @error('full_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-envelope me-2 text-gray-500"></i> Email Address *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Phone + WhatsApp --}}
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-phone me-2 text-gray-500"></i> Phone Number *</label>
                                        <div class="input-group">
                                            <select name="phone_country_code" class="form-select @error('phone_country_code') is-invalid @enderror" style="max-width: 120px;" required>
                                                <option value="">Code</option>
                                                @include('partials.country-codes', ['inputName' => 'phone_country_code', 'selected' => old('phone_country_code')])
                                            </select>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="9876543210">
                                        </div>
                                        @error('phone_country_code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fab fa-whatsapp me-2 text-success"></i> WhatsApp Number</label>
                                        <div class="input-group">
                                            <select name="whatsapp_country_code" class="form-select" style="max-width: 120px;">
                                                <option value="">Code</option>
                                                @include('partials.country-codes', ['inputName' => 'whatsapp_country_code', 'selected' => old('whatsapp_country_code')])
                                            </select>
                                            <input type="text" class="form-control" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Optional">
                                        </div>
                                    </div>
                                </div>

                                {{-- DOB + Gender --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-birthday-cake me-2 text-gray-500"></i> Date of Birth *</label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                        @error('date_of_birth')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        <small class="form-text text-muted">Must be between 18–80 years old</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-venus-mars me-2 text-gray-500"></i> Gender *</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                                            <option value="">Select</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="tab-pane fade" id="address" role="tabpanel">
                            <div class="p-3 border rounded-3 mb-4">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-globe me-2 text-gray-500"></i> Country *</label>
                                        <select name="country" id="country" class="form-select @error('country') is-invalid @enderror" required>
                                            <option value="">Select Country</option>
                                        </select>
                                        @error('country')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-flag me-2 text-gray-500"></i> State / Province *</label>
                                        <select name="state" id="state" class="form-select @error('state') is-invalid @enderror" required disabled>
                                            <option value="">Select Country First</option>
                                        </select>
                                        @error('state')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-city me-2 text-gray-500"></i> City *</label>
                                        <select name="city" id="city" class="form-select @error('city') is-invalid @enderror" required disabled>
                                            <option value="">Select State First</option>
                                        </select>
                                        @error('city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-mail-bulk me-2 text-gray-500"></i> ZIP / Postal Code *</label>
                                        <input type="text" class="form-control @error('zip_code') is-invalid @enderror" name="zip_code" value="{{ old('zip_code') }}" required>
                                        @error('zip_code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label"><i class="fas fa-home me-2 text-gray-500"></i> Current Address *</label>
                                    <textarea class="form-control @error('current_address') is-invalid @enderror" name="current_address" rows="2" required>{{ old('current_address') }}</textarea>
                                    @error('current_address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="sameAsCurrent" {{ old('communication_address') == old('current_address') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sameAsCurrent">
                                        Communication Address same as Current Address
                                    </label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Communication Address</label>
                                    <textarea class="form-control" name="communication_address" rows="2" id="commAddress">{{ old('communication_address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="tab-pane fade" id="professional" role="tabpanel">
                            <div class="p-3 border rounded-3 mb-4">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-id-badge me-2 text-gray-500"></i> Designation</label>
                                        <input type="text" class="form-control @error('designation') is-invalid @enderror" name="designation" value="{{ old('designation') }}">
                                        @error('designation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-building me-2 text-gray-500"></i> Company Name</label>
                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}">
                                        @error('company_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-chart-line me-2 text-gray-500"></i> Industry Experience</label>
                                    <select class="form-select @error('industry_experience') is-invalid @enderror" name="industry_experience">
                                        <option value="">Select Years</option>
                                        <option value="0-3" {{ old('industry_experience') == '0-3' ? 'selected' : '' }}>0–3 Years</option>
                                        <option value="4-6" {{ old('industry_experience') == '4-6' ? 'selected' : '' }}>4–6 Years</option>
                                        <option value="7-10" {{ old('industry_experience') == '7-10' ? 'selected' : '' }}>7–10 Years</option>
                                        <option value="10+" {{ old('industry_experience') == '10+' ? 'selected' : '' }}>10+ Years</option>
                                    </select>
                                    @error('industry_experience')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Document Information -->
                        <div class="tab-pane fade" id="document" role="tabpanel">
                            <div class="p-3 border rounded-3 mb-4">
                                <div class="mb-4">
                                    <label class="form-label"><i class="fas fa-fingerprint me-2 text-gray-500"></i> National / Civil ID</label>
                                    <input type="text" class="form-control @error('civil_id') is-invalid @enderror" name="civil_id" value="{{ old('civil_id') }}">
                                    @error('civil_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label"><i class="fas fa-file-upload me-2 text-gray-500"></i> Upload Civil ID Copy</label>
                                    <input type="file" class="form-control @error('civil_id_file') is-invalid @enderror" name="civil_id_file" accept="image/*,.pdf">
                                    <small class="form-text text-muted">Allowed: JPG, PNG, PDF (Max 5MB)</small>
                                    @error('civil_id_file')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-passport me-2 text-gray-500"></i> Passport Number</label>
                                        <input type="text" class="form-control @error('passport_number') is-invalid @enderror" name="passport_number" value="{{ old('passport_number') }}">
                                        @error('passport_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-calendar-times me-2 text-gray-500"></i> Passport Expiry</label>
                                        <input type="date" class="form-control @error('passport_expiry') is-invalid @enderror" name="passport_expiry" value="{{ old('passport_expiry') }}">
                                        <small class="form-text text-muted">Max 15 years from issue</small>
                                        @error('passport_expiry')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-user-tag me-2 text-gray-500"></i> Residency Type</label>
                                        <select class="form-select @error('residency_type') is-invalid @enderror" name="residency_type">
                                            <option value="">Select</option>
                                            <option value="Visit Visa" {{ old('residency_type') == 'Visit Visa' ? 'selected' : '' }}>Visit Visa</option>
                                            <option value="Work Visa" {{ old('residency_type') == 'Work Visa' ? 'selected' : '' }}>Work Visa</option>
                                            <option value="Residence Visa" {{ old('residency_type') == 'Residence Visa' ? 'selected' : '' }}>Residence Visa</option>
                                            <option value="Permanent Residence" {{ old('residency_type') == 'Permanent Residence' ? 'selected' : '' }}>Permanent Residence</option>
                                            <option value="Citizen" {{ old('residency_type') == 'Citizen' ? 'selected' : '' }}>Citizen</option>
                                        </select>
                                        @error('residency_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-hourglass-end me-2 text-gray-500"></i> Residency Expiry</label>
                                        <input type="date" class="form-control @error('residency_expiry') is-invalid @enderror" name="residency_expiry" value="{{ old('residency_expiry') }}">
                                        <small class="form-text text-muted">Max 5 years from issue</small>
                                        @error('residency_expiry')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Volunteer Information -->
                        <div class="tab-pane fade" id="volunteer" role="tabpanel">
                            <div class="p-3 border rounded-3 mb-4">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-heart me-2 text-gray-500"></i> Volunteer Interests 
                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#interestsModal" class="text-primary">(select interests)</a>
                                    </label>
                                    <textarea class="form-control @error('volunteer_interests') is-invalid @enderror" id="volunteer_interests" name="volunteer_interests" rows="2" placeholder="Click link above to select interests">{{ old('volunteer_interests') }}</textarea>
                                    @error('volunteer_interests')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="tab-pane fade" id="additional" role="tabpanel">
                            <div class="p-3 border rounded-3 mb-4">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-comment-dots me-2 text-gray-500"></i> Anything else you'd like to share?</label>
                                    <textarea class="form-control" name="additional_info" rows="3" placeholder="e.g. Availability, special requests, notes...">{{ old('additional_info') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Account (Security) Information -->
                        <div class="tab-pane fade" id="account" role="tabpanel">
                            <div class="p-3 border rounded-3 mb-4">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-key me-2 text-gray-500"></i> Password *</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                        <small class="form-text text-muted">Minimum 8 characters</small>
                                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-lock-open me-2 text-gray-500"></i> Confirm Password *</label>
                                        <input type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a> *
                                    </label>
                                    @error('terms')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                    </div> <!-- end tab-content -->
                    <div class="d-flex justify-content-between mt-3 mb-4">
                        <button type="button" id="prevBtn" class="btn btn-outline-secondary" disabled>Previous</button>
                        <button type="button" id="nextBtn" class="btn btn-outline-primary">Next</button>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                            <i class="fas fa-user-plus me-2"></i> Create My Account
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-underline">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modals remain unchanged -->
<!-- Volunteer Interests Modal -->
<div class="modal fade" id="interestsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Volunteer Interests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Click on interests to add them:</p>
                <div id="interestsList">
                    <span class="interest-badge" data-interest="Teaching">Teaching</span>
                    <span class="interest-badge" data-interest="Healthcare">Healthcare</span>
                    <span class="interest-badge" data-interest="Environment">Environment</span>
                    <span class="interest-badge" data-interest="Animal Care">Animal Care</span>
                    <span class="interest-badge" data-interest="Arts">Arts</span>
                    <span class="interest-badge" data-interest="Sports">Sports</span>
                    <span class="interest-badge" data-interest="Technology">Technology</span>
                    <span class="interest-badge" data-interest="Disaster Relief">Disaster Relief</span>
                    <span class="interest-badge" data-interest="Elderly Care">Elderly Care</span>
                    <span class="interest-badge" data-interest="Youth Mentorship">Youth Mentorship</span>
                    <span class="interest-badge" data-interest="Fundraising">Fundraising</span>
                    <span class="interest-badge" data-interest="Translation">Translation</span>
                </div>
                <hr>
                <textarea class="form-control" id="previewInterests" rows="2" readonly></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="applyInterests()">Apply to Form</button>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Terms & Conditions</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>By registering, you agree to our terms. All information must be accurate...</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === Address Sync ===
    const sameAsCurrent = document.getElementById('sameAsCurrent');
    const currentAddr = document.querySelector('textarea[name="current_address"]');
    const commAddr = document.getElementById('commAddress');

    if (sameAsCurrent && currentAddr && commAddr) {
        sameAsCurrent.addEventListener('change', function () {
            commAddr.value = this.checked ? currentAddr.value : '';
        });
        currentAddr.addEventListener('input', function () {
            if (sameAsCurrent.checked) commAddr.value = this.value;
        });
    }

    // === Volunteer Interests ===
    let selectedInterests = new Set();
    const interestsTextarea = document.getElementById('volunteer_interests');
    if (interestsTextarea && interestsTextarea.value) {
        selectedInterests = new Set(
            interestsTextarea.value
                .split(',')
                .map(s => s.trim())
                .filter(s => s)
        );
        updateInterestBadges();
        updatePreview();
    }

    function updateInterestBadges() {
        document.querySelectorAll('.interest-badge').forEach(badge => {
            const interest = badge.getAttribute('data-interest');
            if (selectedInterests.has(interest)) {
                badge.classList.add('bg-primary', 'text-white');
                badge.classList.remove('bg-light');
            } else {
                badge.classList.remove('bg-primary', 'text-white');
                badge.classList.add('bg-light');
            }
        });
    }

    document.querySelectorAll('.interest-badge').forEach(badge => {
        badge.addEventListener('click', function () {
            const interest = this.getAttribute('data-interest');
            if (selectedInterests.has(interest)) {
                selectedInterests.delete(interest);
            } else {
                selectedInterests.add(interest);
            }
            updateInterestBadges();
            updatePreview();
        });
    });

    function updatePreview() {
        document.getElementById('previewInterests').value = Array.from(selectedInterests).join(', ');
    }

    window.applyInterests = function () {
        if (interestsTextarea) {
            interestsTextarea.value = Array.from(selectedInterests).join(', ');
        }
        bootstrap.Modal.getInstance(document.getElementById('interestsModal')).hide();
    };

    // === Date Constraints ===
    const today = new Date();
    const maxDOB = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    const minDOB = new Date(today.getFullYear() - 80, today.getMonth(), today.getDate());
    const dobInput = document.querySelector('[name="date_of_birth"]');
    if (dobInput) {
        dobInput.setAttribute('max', maxDOB.toISOString().split('T')[0]);
        dobInput.setAttribute('min', minDOB.toISOString().split('T')[0]);
    }

    const maxPassport = new Date(today.getFullYear() + 15, today.getMonth(), today.getDate());
    const maxResidency = new Date(today.getFullYear() + 5, today.getMonth(), today.getDate());
    const passportExpiry = document.querySelector('[name="passport_expiry"]');
    const residencyExpiry = document.querySelector('[name="residency_expiry"]');
    if (passportExpiry) passportExpiry.setAttribute('max', maxPassport.toISOString().split('T')[0]);
    if (residencyExpiry) residencyExpiry.setAttribute('max', maxResidency.toISOString().split('T')[0]);

    // === Geo Dropdowns ===
    async function initGeoDropdowns() {
        let geoData = [];
        try {
            const res = await fetch('{{ asset('js/geo-data.json') }}');
            geoData = await res.json();
        } catch (e) {
            console.error('Failed to load geo-data.json', e);
            return;
        }

        const countrySel = document.getElementById('country');
        const stateSel = document.getElementById('state');
        const citySel = document.getElementById('city');

        if (!countrySel || !stateSel || !citySel) return;

        const oldCountry = "{{ old('country') }}";
        const oldState = "{{ old('state') }}";
        const oldCity = "{{ old('city') }}";

        const countries = [...new Set(geoData.map(item => item.name))].sort();
        countries.forEach(name => {
            const opt = document.createElement('option');
            opt.value = name;
            opt.textContent = name;
            if (name === oldCountry) opt.selected = true;
            countrySel.appendChild(opt);
        });

        function populateStates(countryName) {
            stateSel.innerHTML = '<option value="">Select State</option>';
            citySel.innerHTML = '<option value="">Select State First</option>';
            citySel.disabled = true;
            stateSel.disabled = countryName ? false : true;

            if (!countryName) return;
            const country = geoData.find(c => c.name === countryName);
            if (!country?.states) return;

            country.states.forEach(state => {
                const opt = document.createElement('option');
                opt.value = state.name;
                opt.textContent = state.name;
                if (state.name === oldState) opt.selected = true;
                stateSel.appendChild(opt);
            });
        }

        function populateCities(countryName, stateName) {
            citySel.innerHTML = '<option value="">Select City</option>';
            citySel.disabled = true;

            const country = geoData.find(c => c.name === countryName);
            const state = country?.states?.find(s => s.name === stateName);
            if (!state?.cities) return;

            state.cities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city.name;
                opt.textContent = city.name;
                if (city.name === oldCity) opt.selected = true;
                citySel.appendChild(opt);
            });
            citySel.disabled = false;
        }

        countrySel.addEventListener('change', (e) => {
            populateStates(e.target.value);
        });
        stateSel.addEventListener('change', (e) => {
            populateCities(countrySel.value, e.target.value);
        });

        if (oldCountry) {
            countrySel.value = oldCountry;
            populateStates(oldCountry);
            if (oldState) {
                stateSel.value = oldState;
                populateCities(oldCountry, oldState);
            }
        }
    }

    // === Tab Navigation Logic (UNIFIED) ===
    const tabIds = ['personal', 'address', 'professional', 'document', 'volunteer', 'additional', 'account'];
    const tabLinks = document.querySelectorAll('#formTabs .nav-link');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    // Function to activate tab by index
    function goToTab(index) {
        if (index < 0 || index >= tabIds.length) return;

        // Update tab content visibility via Bootstrap
        tabIds.forEach((id, i) => {
            const tabPane = document.getElementById(id);
            const tabLink = tabLinks[i];
            if (i === index) {
                tabPane.classList.add('show', 'active');
                tabLink.classList.add('active');
            } else {
                tabPane.classList.remove('show', 'active');
                tabLink.classList.remove('active');
            }
        });

        // Update button states
        if (prevBtn) prevBtn.disabled = (index === 0);
        if (nextBtn) nextBtn.disabled = (index === tabIds.length - 1);
        if (submitBtn) submitBtn.disabled = (index !== tabIds.length - 1);
    }

    // Handle tab clicks
    tabLinks.forEach((link, index) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            goToTab(index);
        });
    });

    // Handle Next/Prev buttons
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            const currentActiveIndex = tabIds.findIndex(id =>
                document.getElementById(id).classList.contains('show')
            );
            if (currentActiveIndex > 0) {
                goToTab(currentActiveIndex - 1);
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const currentActiveIndex = tabIds.findIndex(id =>
                document.getElementById(id).classList.contains('show')
            );
            if (currentActiveIndex < tabIds.length - 1) {
                goToTab(currentActiveIndex + 1);
            }
        });
    }

    // Initialize
    goToTab(0); // Start on first tab
    initGeoDropdowns();
});
</script>
@endpush
@endsection 
{{--  want, I can:

Reduce memory usage

Lazy-load states/cities

Convert this to API-based loading

Add search (Select2) --}}