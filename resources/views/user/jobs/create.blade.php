@extends('layouts.app')

@section('title', 'Post a Job')

@section('header', 'Post a New Job')
@section('subheader', 'Fill in the details below')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body">
                <form id="jobForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="job_title" class="form-label">Job Title *</label>
                        <input type="text" class="form-control" id="job_title" name="job_title" required>
                        <div class="invalid-feedback" id="job_title_error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="job_description" class="form-label">Job Description *</label>
                        <textarea class="form-control" id="job_description" name="job_description" rows="5" required></textarea>
                        <div class="invalid-feedback" id="job_description_error"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="industry" class="form-label">Industry *</label>
                            <input type="text" class="form-control" id="industry" name="industry" required>
                            <div class="invalid-feedback" id="industry_error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="job_type" class="form-label">Job Type *</label>
                            <select class="form-select" id="job_type" name="job_type" required>
                                <option value="">Select Type</option>
                                <option value="full_time">Full Time</option>
                                <option value="part_time">Part Time</option>
                                <option value="contract">Contract</option>
                                <option value="remote">Remote</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                            <div class="invalid-feedback" id="job_type_error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="work_location" class="form-label">Work Location *</label>
                            <input type="text" class="form-control" id="work_location" name="work_location" required>
                            <div class="invalid-feedback" id="work_location_error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="salary_range" class="form-label">Salary Range *</label>
                            <input type="text" class="form-control" id="salary_range" name="salary_range" 
                                   placeholder="e.g., $50,000 - $70,000" required>
                            <div class="invalid-feedback" id="salary_range_error"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="app_deadline" class="form-label">Application Deadline *</label>
                        <input type="date" class="form-control" id="app_deadline" name="app_deadline" required>
                        <div class="form-text">Deadline must be a future date.</div>
                        <div class="invalid-feedback" id="app_deadline_error"></div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('user.jobs.my-jobs') }}" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="submitText">Submit for Approval</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Important Notes</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>All job postings require admin approval before being visible to other users.</li>
                    <li>You will receive an email notification when your job is approved or rejected.</li>
                    <li>Jobs automatically expire after the application deadline.</li>
                    <li>Ensure all information is accurate before submitting.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Set minimum date for deadline (tomorrow)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('app_deadline').min = tomorrow.toISOString().split('T')[0];
    
    // Form submission
    $('#jobForm').submit(function(e) {
        e.preventDefault();
        
        const btn = $('#submitBtn');
        const submitText = $('#submitText');
        const spinner = $('#spinner');
        
        // Reset validation
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Show loading state
        btn.prop('disabled', true);
        submitText.text('Submitting...');
        spinner.removeClass('d-none');
        
        $.ajax({
            url: '{{ route("user.jobs.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                showToast('success', response.message);
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 2000);
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                submitText.text('Submit for Approval');
                spinner.addClass('d-none');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}_error`).text(errors[field][0]);
                    }
                    showToast('error', 'Please fix the errors in the form.');
                } else {
                    showToast('error', xhr.responseJSON?.message || 'An error occurred. Please try again.');
                }
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