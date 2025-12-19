@extends('layouts.app')

@section('title', 'Register')

@section('header', 'Create Account')
@section('subheader', 'Join our job and offer portal')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" id="registrationForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Personal Information -->
                            <h5 class="mb-3 border-bottom pb-2">Personal Information</h5>
                            
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input id="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                       name="full_name" value="{{ old('full_name') }}" required autocomplete="name" autofocus>
                                @error('full_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone') }}" required autocomplete="tel">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                @error('date_of_birth')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender *</label>
                                <select id="gender" class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Address Information -->
                            <h5 class="mb-3 mt-4 border-bottom pb-2">Address Information</h5>
                            
                            <div class="mb-3">
                                <label for="country" class="form-label">Country *</label>
                                <select id="country" class="form-select @error('country') is-invalid @enderror" name="country" required>
                                    <option value="">Select Country</option>
                                    <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                                    <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>UK</option>
                                    <option value="UAE" {{ old('country') == 'UAE' ? 'selected' : '' }}>UAE</option>
                                    <option value="Saudi Arabia" {{ old('country') == 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                                    <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="current_address" class="form-label">Current Address *</label>
                                <textarea id="current_address" class="form-control @error('current_address') is-invalid @enderror" 
                                          name="current_address" rows="3" required>{{ old('current_address') }}</textarea>
                                @error('current_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Professional Information -->
                            <h5 class="mb-3 border-bottom pb-2">Professional Information</h5>
                            
                            <div class="mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input id="occupation" type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                       name="occupation" value="{{ old('occupation') }}">
                                @error('occupation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="company" class="form-label">Company</label>
                                <input id="company" type="text" class="form-control @error('company') is-invalid @enderror" 
                                       name="company" value="{{ old('company') }}">
                                @error('company')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="skills" class="form-label">Skills</label>
                                <textarea id="skills" class="form-control @error('skills') is-invalid @enderror" 
                                          name="skills" rows="2" placeholder="Enter skills separated by commas">{{ old('skills') }}</textarea>
                                <small class="text-muted">Example: PHP, Laravel, JavaScript, MySQL</small>
                                @error('skills')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Document Information -->
                            <h5 class="mb-3 mt-4 border-bottom pb-2">Document Information</h5>
                            
                            <div class="mb-3">
                                <label for="civil_id" class="form-label">Civil ID</label>
                                <input id="civil_id" type="text" class="form-control @error('civil_id') is-invalid @enderror" 
                                       name="civil_id" value="{{ old('civil_id') }}">
                                @error('civil_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="passport_number" class="form-label">Passport Number</label>
                                <input id="passport_number" type="text" class="form-control @error('passport_number') is-invalid @enderror" 
                                       name="passport_number" value="{{ old('passport_number') }}">
                                @error('passport_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="passport_expiry" class="form-label">Passport Expiry Date</label>
                                <input id="passport_expiry" type="date" class="form-control @error('passport_expiry') is-invalid @enderror" 
                                       name="passport_expiry" value="{{ old('passport_expiry') }}">
                                @error('passport_expiry')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="residency_type" class="form-label">Residency Type</label>
                                <select id="residency_type" class="form-select @error('residency_type') is-invalid @enderror" name="residency_type">
                                    <option value="">Select Residency Type</option>
                                    <option value="Visit Visa" {{ old('residency_type') == 'Visit Visa' ? 'selected' : '' }}>Visit Visa</option>
                                    <option value="Work Visa" {{ old('residency_type') == 'Work Visa' ? 'selected' : '' }}>Work Visa</option>
                                    <option value="Residence Visa" {{ old('residency_type') == 'Residence Visa' ? 'selected' : '' }}>Residence Visa</option>
                                    <option value="Permanent Residence" {{ old('residency_type') == 'Permanent Residence' ? 'selected' : '' }}>Permanent Residence</option>
                                    <option value="Citizen" {{ old('residency_type') == 'Citizen' ? 'selected' : '' }}>Citizen</option>
                                </select>
                                @error('residency_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="residency_expiry" class="form-label">Residency Expiry Date</label>
                                <input id="residency_expiry" type="date" class="form-control @error('residency_expiry') is-invalid @enderror" 
                                       name="residency_expiry" value="{{ old('residency_expiry') }}">
                                @error('residency_expiry')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Volunteer Information -->
                            <h5 class="mb-3 mt-4 border-bottom pb-2">Volunteer Information</h5>
                            
                            <div class="mb-3">
                                <label for="volunteer_interests" class="form-label">Volunteer Interests</label>
                                <textarea id="volunteer_interests" class="form-control @error('volunteer_interests') is-invalid @enderror" 
                                          name="volunteer_interests" rows="2" placeholder="Enter volunteer interests separated by commas">{{ old('volunteer_interests') }}</textarea>
                                <small class="text-muted">Example: Teaching, Healthcare, Environment, Animal Care</small>
                                @error('volunteer_interests')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <h5 class="mb-3 mt-4 border-bottom pb-2">Account Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">Minimum 8 characters</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password-confirm" class="form-label">Confirm Password *</label>
                            <input id="password-confirm" type="password" class="form-control" 
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a> *
                            </label>
                            @error('terms')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Register Account
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <p class="mb-0">Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none">Login here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms & Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Terms content here -->
                <h6>1. Account Registration</h6>
                <p>All users must provide accurate information during registration.</p>
                
                <h6>2. Email Verification</h6>
                <p>Email verification is required to activate your account.</p>
                
                <h6>3. Content Posting</h6>
                <p>All jobs and offers require admin approval before being visible to other users.</p>
                
                <h6>4. User Conduct</h6>
                <p>Users must not post inappropriate content, spam, or misleading information.</p>
                
                <h6>5. Account Suspension</h6>
                <p>Admins reserve the right to suspend accounts for violations of terms.</p>
                
                <h6>6. Privacy</h6>
                <p>We respect your privacy and protect your personal information according to our privacy policy.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set max date for date of birth (18 years ago)
    const dobInput = document.getElementById('date_of_birth');
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    dobInput.max = maxDate.toISOString().split('T')[0];
    
    // Set min date for expiry dates (tomorrow)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    document.getElementById('passport_expiry').min = tomorrowStr;
    document.getElementById('residency_expiry').min = tomorrowStr;
});
</script>
@endpush
@endsection