@extends('layouts.app')

@section('title', 'Register')

@section('header', 'Create Account')
@section('subheader', 'Join our job and offer portal')

@push('styles')
<style>
    /* Premium Enhancements */
    .registration-wrapper {
        min-height: calc(100vh - 200px);
        padding: 2rem 0;
        width:100%
    }
    
    .form-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    
.form-tabs-container {
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
    padding: 0 1.5rem;
}

.form-tabs {
    display: flex;
    overflow-x: auto;
    scrollbar-width: none;
    gap: 0.75rem;
    padding: 0.75rem 0;
}

.form-tabs::-webkit-scrollbar {
    display: none;
}

.form-tab {
    flex: 1;
    min-width: 120px;
    padding: 0.875rem 1.25rem;
    border: 2px solid #e5e7eb;
    background: white;
    border-radius: 10px;
    color: #6b7280;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    white-space: nowrap;
}

.form-tab:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    transform: translateY(-2px);
}

.form-tab.active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.form-tab i {
    font-size: 0.9rem;
}
    
    /* Form Content */
    .form-content {
        padding: 2.5rem;
    }
    
    /* Tab Content */
    .tab-pane {
        display: none;
        animation: fadeIn 0.3s ease-out;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Form Sections */
    .form-section {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title i {
        color: #3b82f6;
    }
    
    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-label i {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .input-group .form-select {
        border-right: none;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .input-group .form-control {
        border-left: none;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    /* Navigation Buttons */
    .form-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 0;
        margin-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .nav-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-outline-secondary {
        border: 2px solid #d1d5db;
        color: #6b7280;
    }
    
    .btn-outline-secondary:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    
    .btn-outline-primary {
        border: 2px solid #3b82f6;
        color: #3b82f6;
    }
    
    .btn-outline-primary:hover {
        background: #3b82f6;
        color: white;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        padding: 0.875rem 2rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }
    
    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }
    
    /* Progress Bar */
    .form-progress {
        padding: 0 1.5rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .progress-container {
        padding: 1rem 0;
    }
    
    .progress-bar {
        height: 6px;
        background: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%);
        border-radius: 3px;
        transition: width 0.3s ease;
    }
    
    .progress-text {
        text-align: center;
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    /* Interest Badges */
    .interest-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.75rem;
        margin: 1rem 0;
    }
    
    .interest-badge {
        padding: 0.75rem 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
        color: #4b5563;
    }
    
    .interest-badge:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        transform: translateY(-1px);
    }
    
    .interest-badge.selected {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    /* Terms Checkbox */
    .terms-container {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    /* Error States */
    .alert-danger {
        border: none;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #dc2626;
        border-radius: 8px;
    }
    
    .is-invalid {
        border-color: #dc2626 !important;
    }
    
    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .form-content {
            padding: 1.5rem;
        }
        
        .form-section {
            padding: 1.5rem;
        }
        
    .form-tabs {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 tabs per row */
        overflow-x: hidden; /* disable scroll */
        gap: 0.6rem;
        padding: 0.6rem 0;
    }

    .form-tab {
        min-width: auto; /* allow shrinking */
        padding: 0.65rem 0.25rem;
        font-size: 0.82rem;
        white-space: normal; /* allow text wrap if needed */
    }
        
        .interest-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
        
        .nav-btn {
            padding: 0.625rem 1.25rem;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        .form-content {
            padding: 1.25rem;
        }
        
        .form-section {
            padding: 1.25rem;
        }
        
        .form-tab {
            min-width: 80px;
            padding: 0.625rem 0.75rem;
            font-size: 0.85rem;
        }
        
        .nav-btn {
            padding: 0.5rem 1rem;
        }
    }
        @media (max-width: 480px) {
        .form-tabs {
            grid-template-columns: repeat(2, 1fr); /* 2 tabs per row */
        }
    }
</style>
@endpush

@section('content')
<div class="registration-wrapper">
    <div class="container">
        <div class="row justify-content-center">
                <div class="col-xl-11 col-lg-11 col-md-12 px-2 px-md-3">
                    <div class="form-container">
                    <!-- Progress Bar -->
                    <div class="form-progress">
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill" style="width: 14.28%"></div>
                            </div>
                            <div class="progress-text" id="progressText">Step 1 of 7 - Personal Information</div>
                        </div>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="form-tabs-container">
                        <div class="form-tabs" id="formTabs">
                            <button class="form-tab active" data-tab="personal">
                                <i class="fas fa-user"></i> Personal
                            </button>
                            <button class="form-tab" data-tab="address">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </button>
                            <button class="form-tab" data-tab="professional">
                                <i class="fas fa-briefcase"></i> Professional
                            </button>
                            <button class="form-tab" data-tab="document">
                                <i class="fas fa-file-alt"></i> Document
                            </button>
                            <button class="form-tab" data-tab="volunteer">
                                <i class="fas fa-hands-helping"></i> Volunteer
                            </button>
                            <button class="form-tab" data-tab="additional">
                                <i class="fas fa-comment-alt"></i> Additional
                            </button>
                            <button class="form-tab" data-tab="account">
                                <i class="fas fa-lock"></i> Password
                            </button>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div class="form-content">
                        {{-- Global Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <div>
                                        <strong>Registration failed!</strong>
                                        <ul class="mb-0 mt-1 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li class="small">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registrationForm" enctype="multipart/form-data">
                            @csrf

                            @if(request()->has('ref'))
                                <input type="hidden" name="referral_code" value="{{ request('ref') }}">
                            @endif

                            <!-- Personal Information -->
                            <div class="tab-pane active" id="personal">
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-user-circle"></i> Personal Details</h3>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-signature"></i> Full Name *</label>
                                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                                   name="full_name" value="{{ old('full_name') }}" required autofocus>
                                            @error('full_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-envelope"></i> Email Address *</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   name="email" value="{{ old('email') }}" required>
                                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-phone"></i> Phone Number *</label>
                                            <div class="input-group">
                                                <select name="phone_country_code" class="form-select @error('phone_country_code') is-invalid @enderror" style="max-width: 120px;" required>
                                                    <option value="">Code</option>
                                                    @include('partials.country-codes', ['inputName' => 'phone_country_code', 'selected' => old('phone_country_code')])
                                                </select>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                       name="phone" value="{{ old('phone') }}" required placeholder="9876543210">
                                            </div>
                                            @error('phone_country_code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fab fa-whatsapp"></i> WhatsApp Number</label>
                                            <div class="input-group">
                                                <select name="whatsapp_country_code" class="form-select" style="max-width: 120px;">
                                                    <option value="">Code</option>
                                                    @include('partials.country-codes', ['inputName' => 'whatsapp_country_code', 'selected' => old('whatsapp_country_code')])
                                                </select>
                                                <input type="text" class="form-control" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Optional">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-calendar-alt"></i> Date of Birth *</label>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                   name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                            @error('date_of_birth')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            <small class="text-muted mt-1 d-block">Must be between 18–80 years old</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-venus-mars"></i> Gender *</label>
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
                            <div class="tab-pane" id="address">
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Address Information</h3>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-globe"></i> Country *</label>
                                            <select name="country" id="country" class="form-select @error('country') is-invalid @enderror" required>
                                                <option value="">Select Country</option>
                                            </select>
                                            @error('country')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-flag"></i> State / Province *</label>
                                            <select name="state" id="state" class="form-select @error('state') is-invalid @enderror" required disabled>
                                                <option value="">Select Country First</option>
                                            </select>
                                            @error('state')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-city"></i> City *</label>
                                            <select name="city" id="city" class="form-select @error('city') is-invalid @enderror" required disabled>
                                                <option value="">Select State First</option>
                                            </select>
                                            @error('city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-mail-bulk"></i> ZIP / Postal Code *</label>
                                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" 
                                                   name="zip_code" value="{{ old('zip_code') }}" required>
                                            @error('zip_code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label"><i class="fas fa-home"></i> Current Address *</label>
                                        <textarea class="form-control @error('current_address') is-invalid @enderror" 
                                                  name="current_address" rows="2" required>{{ old('current_address') }}</textarea>
                                        @error('current_address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="sameAsCurrent" {{ old('communication_address') == old('current_address') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sameAsCurrent">
                                            Communication Address same as Current Address
                                        </label>
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label">Communication Address</label>
                                        <textarea class="form-control" name="communication_address" rows="2" id="commAddress">{{ old('communication_address') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Professional Information -->
                            <div class="tab-pane" id="professional">
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-briefcase"></i> Professional Details</h3>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-id-badge"></i> Designation</label>
                                            <input type="text" class="form-control @error('designation') is-invalid @enderror" 
                                                   name="designation" value="{{ old('designation') }}">
                                            @error('designation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-building"></i> Company Name</label>
                                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                                   name="company_name" value="{{ old('company_name') }}">
                                            @error('company_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label"><i class="fas fa-chart-line"></i> Industry Experience</label>
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
                            <div class="tab-pane" id="document">
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-file-alt"></i> Document Details</h3>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-fingerprint"></i> National / Civil ID</label>
                                        <input type="text" class="form-control @error('civil_id') is-invalid @enderror" 
                                               name="civil_id" value="{{ old('civil_id') }}">
                                        @error('civil_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-upload"></i> Upload Civil ID Copy</label>
                                        <input type="file" class="form-control @error('civil_id_file') is-invalid @enderror" 
                                               name="civil_id_file" accept="image/*,.pdf">
                                        <small class="text-muted d-block mt-1">Allowed: JPG, PNG, PDF (Max 5MB)</small>
                                        @error('civil_id_file')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-passport"></i> Passport Number</label>
                                            <input type="text" class="form-control @error('passport_number') is-invalid @enderror" 
                                                   name="passport_number" value="{{ old('passport_number') }}">
                                            @error('passport_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-calendar-times"></i> Passport Expiry</label>
                                            <input type="date" class="form-control @error('passport_expiry') is-invalid @enderror" 
                                                   name="passport_expiry" value="{{ old('passport_expiry') }}">
                                            <small class="text-muted d-block mt-1">Max 15 years from issue</small>
                                            @error('passport_expiry')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-user-tag"></i> Residency Type</label>
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
                                            <label class="form-label"><i class="fas fa-hourglass-end"></i> Residency Expiry</label>
                                            <input type="date" class="form-control @error('residency_expiry') is-invalid @enderror" 
                                                   name="residency_expiry" value="{{ old('residency_expiry') }}">
                                            <small class="text-muted d-block mt-1">Max 5 years from issue</small>
                                            @error('residency_expiry')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Volunteer Information -->
                            <div class="tab-pane" id="volunteer">
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-hands-helping"></i> Volunteer Interests</h3>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-heart"></i> Volunteer Interests
                                        </label>
                                        <p class="text-muted mb-2">Click on interests to select them</p>

                                        <!-- Hidden field to store final value -->
                                        <textarea 
                                            class="form-control d-none" 
                                            name="volunteer_interests" 
                                            id="volunteer_interests"
                                        >{{ old('volunteer_interests') }}</textarea>

                                        <!-- Link to open modal -->
                                        <a href="#" class="text-primary text-decoration-underline small" data-bs-toggle="modal" data-bs-target="#selectInterestsModal">
                                            Click here to select your interests
                                        </a>

                                        <!-- Display current selection -->
                                        <div class="mt-2">
                                            <small class="text-muted">Selected:</small>
                                            <span id="selectedInterestsDisplay" class="d-block mt-1">
                                                @if(old('volunteer_interests'))
                                                    {{ implode(', ', array_filter(array_map('trim', explode(',', old('volunteer_interests'))))) }}
                                                @else
                                                    None selected
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Additional info -->
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="tab-pane" id="additional">
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-comment-alt"></i> Additional Information</h3>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-info-circle"></i> Anything else you'd like to share?</label>
                                        <textarea class="form-control" name="additional_info" rows="3" 
                                                  placeholder="e.g. Availability, special requests, notes...">{{ old('additional_info') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Account (Security) Information -->
                            <div class="tab-pane" id="account">
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-lock"></i> Account Security</h3>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-key"></i> Password *</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   name="password" required>
                                            <small class="text-muted d-block mt-1">Minimum 8 characters</small>
                                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-lock-open"></i> Confirm Password *</label>
                                            <input type="password" class="form-control" name="password_confirmation" required>
                                        </div>
                                    </div>

                                    <div class="terms-container mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-decoration-underline">Terms & Conditions</a> *
                                            </label>
                                            @error('terms')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="form-navigation">
                                <button type="button" id="prevBtn" class="nav-btn btn-outline-secondary" disabled>
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                
                                <button type="button" id="nextBtn" class="nav-btn btn-outline-primary">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                
                                <button type="submit" class="nav-btn btn btn-success" id="submitBtn">
                                    <i class="fas fa-user-plus me-2"></i> Create Account
                                </button>
                            </div>

                            <div class="text-center mt-4 pt-3 border-top">
                                <p class="mb-0">Already have an account? 
                                    <a href="{{ route('login') }}" class="text-decoration-underline fw-medium">Login here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select Interests Modal (Profile-Style) -->
<div class="modal fade" id="selectInterestsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Volunteer Interests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Select your interests:</p>
                <div id="profileInterestsList" class="mb-3">
                    @php
                    $allInterests = [
                        'Teaching', 'Healthcare', 'Environment', 'Animal Care',
                        'Arts', 'Sports', 'Technology', 'Disaster Relief',
                        'Elderly Care', 'Youth Mentorship', 'Fundraising', 'Translation'
                    ];
                    $selectedInterests = old('volunteer_interests')
                        ? array_filter(array_map('trim', explode(',', old('volunteer_interests'))))
                        : [];
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="applyInterestsBtn">Apply Selection</button>
            </div>
        </div>
    </div>
</div>
<!-- Volunteer Interests Selection Modal -->
<div class="modal fade" id="selectInterestsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-heart text-danger me-2"></i> Volunteer Interests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Select your areas of interest:</p>
                <div class="row g-3" id="interestsCheckboxGrid">
                    @php
                    $allInterests = [
                        'Teaching', 'Healthcare', 'Environment', 'Animal Care',
                        'Arts', 'Sports', 'Technology', 'Disaster Relief',
                        'Elderly Care', 'Youth Mentorship', 'Fundraising', 'Translation'
                    ];
                    // Use old() to preserve selections on validation errors
                    $selected = old('volunteer_interests') 
                        ? array_filter(array_map('trim', explode(',', old('volunteer_interests'))))
                        : [];
                    @endphp

                    @foreach($allInterests as $interest)
                        <div class="col-md-6">
                            <div class="form-check d-flex align-items-center">
                                <input 
                                    class="form-check-input interest-checkbox me-2" 
                                    type="checkbox" 
                                    value="{{ $interest }}" 
                                    id="chk_{{ Str::slug($interest) }}"
                                    {{ in_array($interest, $selected) ? 'checked' : '' }}
                                >
                                <label class="form-check-label mb-0" for="chk_{{ Str::slug($interest) }}">
                                    {{ $interest }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="applyInterestsBtn">Apply Selection</button>
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
    // === Tab Navigation System ===
    const tabs = document.querySelectorAll('.form-tab');
    const tabPanes = document.querySelectorAll('.tab-pane');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    
    const tabOrder = ['personal', 'address', 'professional', 'document', 'volunteer', 'additional', 'account'];
    const tabLabels = [
        'Personal Information',
        'Address Information',
        'Professional Information',
        'Document Information',
        'Volunteer Interests',
        'Additional Information',
        'Account Security'
    ];
    
    let currentTabIndex = 0;
    
    // Function to show tab
    function showTab(index) {
        // Hide all tabs
        tabPanes.forEach(pane => {
            pane.classList.remove('active');
        });
        
        // Remove active class from all tab buttons
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show selected tab
        tabPanes[index].classList.add('active');
        tabs[index].classList.add('active');
        
        // Update button states
        prevBtn.disabled = index === 0;
        nextBtn.style.display = index === tabOrder.length - 1 ? 'none' : 'block';
        submitBtn.style.display = index === tabOrder.length - 1 ? 'block' : 'none';
        
        // Update progress
        const progress = ((index + 1) / tabOrder.length) * 100;
        progressFill.style.width = `${progress}%`;
        progressText.textContent = `Step ${index + 1} of ${tabOrder.length} - ${tabLabels[index]}`;
        
        currentTabIndex = index;
    }
    
    // Tab click event
    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => {
            showTab(index);
        });
    });
    
    // Next button event
    nextBtn.addEventListener('click', () => {
        if (currentTabIndex < tabOrder.length - 1) {
            if (validateCurrentTab()) {
                showTab(currentTabIndex + 1);
            }
        }
    });
    
    // Previous button event
    prevBtn.addEventListener('click', () => {
        if (currentTabIndex > 0) {
            showTab(currentTabIndex - 1);
        }
    });
    
    // Validate current tab
    function validateCurrentTab() {
        const currentTab = tabPanes[currentTabIndex];
        const requiredFields = currentTab.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    }
    
    // Initialize first tab
    showTab(0);
    
    // === Address Sync ===
    const sameAsCurrent = document.getElementById('sameAsCurrent');
    const currentAddr = document.querySelector('textarea[name="current_address"]');
    const commAddr = document.getElementById('commAddress');
    
    if (sameAsCurrent && currentAddr && commAddr) {
        sameAsCurrent.addEventListener('change', function() {
            commAddr.value = this.checked ? currentAddr.value : '';
        });
        
        currentAddr.addEventListener('input', function() {
            if (sameAsCurrent.checked) {
                commAddr.value = this.value;
            }
        });
    }
    
    // === Volunteer Interests ===
    const interestsGrid = document.getElementById('interestsGrid');
    const interestsTextarea = document.getElementById('volunteer_interests');
    let selectedInterests = new Set();
    
    // Load existing interests
    if (interestsTextarea && interestsTextarea.value) {
        selectedInterests = new Set(
            interestsTextarea.value
                .split(',')
                .map(s => s.trim())
                .filter(s => s)
        );
        updateInterestBadges();
    }
    
    // Interest selection
    if (interestsGrid) {
        interestsGrid.addEventListener('click', function(e) {
            const badge = e.target.closest('.interest-badge');
            if (!badge) return;
            
            const interest = badge.dataset.interest;
            if (selectedInterests.has(interest)) {
                selectedInterests.delete(interest);
                badge.classList.remove('selected');
            } else {
                selectedInterests.add(interest);
                badge.classList.add('selected');
            }
            
            updateInterestsTextarea();
        });
    }
    
    function updateInterestBadges() {
        document.querySelectorAll('.interest-badge').forEach(badge => {
            const interest = badge.dataset.interest;
            if (selectedInterests.has(interest)) {
                badge.classList.add('selected');
            }
        });
    }
    
    function updateInterestsTextarea() {
        if (interestsTextarea) {
            interestsTextarea.value = Array.from(selectedInterests).join(', ');
        }
    }

const modal = document.getElementById('selectInterestsModal');
    if (!modal) return;

    // Load initial selection from hidden textarea
    const hiddenTextarea = document.getElementById('volunteer_interests');
    let selected = new Set();
    if (hiddenTextarea.value) {
        selected = new Set(
            hiddenTextarea.value
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
            updateBadges();
        });
    });

    // Apply button
    document.getElementById('applyInterestsBtn').addEventListener('click', function() {
        // Save to hidden textarea
        hiddenTextarea.value = Array.from(selected).join(', ');
        
        // Update display
        const display = document.getElementById('selectedInterestsDisplay');
        display.textContent = selected.size ? Array.from(selected).join(', ') : 'None selected';
        
        // Close modal
        bootstrap.Modal.getInstance(modal).hide();
    });

    // Initialize visuals
    updateBadges();

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
    
    // === Geo Dropdowns (keep your existing code) ===
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
    
    // Initialize
    initGeoDropdowns();
});
</script>
@endpush
@endsection