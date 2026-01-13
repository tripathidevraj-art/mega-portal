@extends('layouts.app')

@section('title', 'Post a Job')

@section('header', 'Post a New Job')
@section('subheader', 'Fill in the details below')

@push('styles')
<style>
    .post-job-container {
        min-height: calc(100vh - 200px);
        padding: 2rem 0;
    }
    
    .post-job-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease;
    }
    
    .post-job-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
    }
    
    .card-header-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-bottom: none;
        padding: 2rem;
    }
    
    .card-header-gradient h3 {
        color: white;
        margin: 0;
    }
    
    .card-header-gradient .subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1rem;
        margin-top: 0.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-label i {
        color: #10b981;
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc2626;
    }
    
    .invalid-feedback {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        padding: 0.875rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }
    
    .btn-primary:disabled {
        opacity: 0.7;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        border: none;
        padding: 0.875rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
    }
    
    .info-card-header {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #d1d5db;
    }
    
    .info-card-header h6 {
        margin: 0;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .info-card-body {
        padding: 1.5rem;
    }
    
    .info-card-body ul {
        margin: 0;
        padding-left: 1.25rem;
    }
    
    .info-card-body li {
        margin-bottom: 0.75rem;
        color: #4b5563;
        line-height: 1.5;
    }
    
    .info-card-body li:last-child {
        margin-bottom: 0;
    }
    
    .info-card-body i {
        color: #10b981;
        margin-right: 10px;
    }
    
    .required-star {
        color: #dc2626;
        margin-left: 4px;
    }
    
    .form-text {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .character-counter {
        text-align: right;
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }
    
    .character-counter.warning {
        color: #f59e0b;
    }
    
    .character-counter.danger {
        color: #dc2626;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .post-job-container {
            padding: 1rem;
        }
        
        .card-header-gradient {
            padding: 1.5rem;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .d-md-flex {
            flex-direction: column;
        }
    }
    
    @media (max-width: 576px) {
        .card-header-gradient h3 {
            font-size: 1.5rem;
        }
        
        .form-control, .form-select {
            padding: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="post-job-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                                <!-- Information Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h6><i class="fas fa-info-circle text-success"></i>Important Information</h6>
                    </div>
                    <div class="info-card-body">
                        <ul>
                            <li><i class="fas fa-check-circle"></i>All job postings require admin approval before being published</li>
                            <li><i class="fas fa-bell"></i>You will receive email notifications about approval status</li>
                            <li><i class="fas fa-clock"></i>Jobs automatically expire after the application deadline</li>
                            <li><i class="fas fa-eye"></i>Ensure all information is accurate before submission</li>
                            <li><i class="fas fa-history"></i>Average approval time is 24-48 hours</li>
                            <li><i class="fas fa-edit"></i>You can edit your job posting before it's approved</li>
                        </ul>
                    </div>
                </div>
                <!-- Main Form Card -->
                <div class="post-job-card">
                    <!-- Card Header -->
                    <div class="card-header-gradient">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <h3>Post a New Job Opening</h3>
                                <p class="subtitle">Reach qualified candidates for your position</p>
                            </div>
                            <span class="badge bg-white text-success px-3 py-2 mt-2 mt-sm-0">
                                <i class="fas fa-shield-alt me-1"></i>Requires Approval
                            </span>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body p-4 p-md-5">
                        <form id="jobForm">
                            @csrf
                            
                            <!-- Job Title -->
                            <div class="mb-4">
                                <label for="job_title" class="form-label">
                                    <i class="fas fa-heading"></i>Job Title <span class="required-star">*</span>
                                </label>
                                <input type="text" class="form-control" id="job_title" name="job_title" 
                                       placeholder="e.g., Senior Software Engineer" required>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb"></i>Be specific to attract the right candidates
                                </div>
                                <div class="invalid-feedback" id="job_title_error"></div>
                            </div>
                            
                            <!-- Job Description -->
                            <div class="mb-4">
                                <label for="job_description" class="form-label">
                                    <i class="fas fa-align-left"></i>Job Description <span class="required-star">*</span>
                                </label>
                                <textarea class="form-control" id="job_description" name="job_description" 
                                          rows="6" placeholder="Describe the role, responsibilities, and requirements..."
                                          required></textarea>
                                <div class="character-counter" id="descriptionCounter">0/5000 characters</div>
                                <div class="invalid-feedback" id="job_description_error"></div>
                            </div>
                            
                            <!-- Industry & Job Type -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="industry" class="form-label">
                                        <i class="fas fa-industry"></i>Industry <span class="required-star">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="industry" name="industry" 
                                           placeholder="e.g., Technology, Healthcare, Finance" required>
                                    <div class="invalid-feedback" id="industry_error"></div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="job_type" class="form-label">
                                        <i class="fas fa-briefcase"></i>Job Type <span class="required-star">*</span>
                                    </label>
                                    <select class="form-select" id="job_type" name="job_type" required>
                                        <option value="">Select Employment Type</option>
                                        <option value="full_time">Full Time</option>
                                        <option value="part_time">Part Time</option>
                                        <option value="contract">Contract</option>
                                        <option value="remote">Remote</option>
                                        <option value="hybrid">Hybrid</option>
                                        <option value="internship">Internship</option>
                                        <option value="temporary">Temporary</option>
                                    </select>
                                    <div class="invalid-feedback" id="job_type_error"></div>
                                </div>
                            </div>
                            
                            <!-- Work Location & Salary -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="work_location" class="form-label">
                                        <i class="fas fa-map-marker-alt"></i>Work Location <span class="required-star">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="work_location" name="work_location" 
                                           placeholder="e.g., New York, NY or Remote" required>
                                    <div class="invalid-feedback" id="work_location_error"></div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="salary_range" class="form-label">
                                        <i class="fas fa-money-bill-wave"></i>Salary Range <span class="required-star">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="salary_range" name="salary_range" 
                                           placeholder="e.g., $50,000 - $70,000 per year" required>
                                    <div class="invalid-feedback" id="salary_range_error"></div>
                                </div>
                            </div>
                            
                            <!-- Application Deadline -->
                            <div class="mb-4">
                                <label for="app_deadline" class="form-label">
                                    <i class="fas fa-calendar-alt"></i>Application Deadline <span class="required-star">*</span>
                                </label>
                                <input type="date" class="form-control" id="app_deadline" name="app_deadline" required>
                                <div class="form-text">
                                    <i class="fas fa-clock"></i>Deadline must be at least 7 days from today
                                </div>
                                <div class="invalid-feedback" id="app_deadline_error"></div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center pt-4 border-top">
                                <a href="{{ route('user.jobs.my-jobs') }}" class="btn btn-secondary mb-3 mb-md-0">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span id="submitText">
                                        <i class="fas fa-paper-plane me-2"></i>Submit for Approval
                                    </span>
                                    <span id="spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for deadline (7 days from now)
    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 7);
    document.getElementById('app_deadline').min = minDate.toISOString().split('T')[0];
    
    // Character counter for job description
    const descriptionTextarea = document.getElementById('job_description');
    const descriptionCounter = document.getElementById('descriptionCounter');
    
    if (descriptionTextarea && descriptionCounter) {
        descriptionTextarea.addEventListener('input', function() {
            const length = this.value.length;
            descriptionCounter.textContent = `${length}/5000 characters`;
            
            // Update color based on length
            descriptionCounter.className = 'character-counter';
            if (length > 4500) {
                descriptionCounter.classList.add('warning');
            } else if (length > 4800) {
                descriptionCounter.classList.add('danger');
            }
        });
        
        // Trigger initial count
        descriptionTextarea.dispatchEvent(new Event('input'));
    }
    
    // Form submission
    const jobForm = document.getElementById('jobForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const spinner = document.getElementById('spinner');
    
    if (jobForm && submitBtn) {
        jobForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset validation
            const formControls = this.querySelectorAll('.form-control, .form-select');
            formControls.forEach(control => {
                control.classList.remove('is-invalid');
            });
            
            const errorMessages = this.querySelectorAll('.invalid-feedback');
            errorMessages.forEach(msg => msg.textContent = '');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            spinner.classList.remove('d-none');
            
            // Submit form via AJAX
            $.ajax({
                url: '{{ route("user.jobs.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    showToast('success', response.message);
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit for Approval';
                    spinner.classList.add('d-none');
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                },
                error: function(xhr) {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit for Approval';
                    spinner.classList.add('d-none');
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            const input = document.getElementById(field);
                            const errorDiv = document.getElementById(`${field}_error`);
                            
                            if (input) {
                                input.classList.add('is-invalid');
                            }
                            
                            if (errorDiv) {
                                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i>${errors[field][0]}`;
                            }
                        }
                        showToast('error', 'Please fix the errors in the form.');
                    } else {
                        showToast('error', xhr.responseJSON?.message || 'An error occurred. Please try again.');
                    }
                }
            });
        });
    }
    
    // Toast notification function
    function showToast(type, message) {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after it hides
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1060';
        document.body.appendChild(container);
        return container;
    }
});
</script>
@endpush