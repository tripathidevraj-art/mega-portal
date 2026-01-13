@extends('layouts.app')

@section('title', 'Apply for ' . $job->job_title)

@push('styles')
<style>
    .apply-container {
        min-height: calc(100vh - 200px);
        padding: 2rem 0;
    }
    
    .application-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease;
    }
    
    .application-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
    }
    
    .card-header-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-bottom: none;
        padding: 2rem;
    }
    
    .job-title-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        margin-left: 1rem;
    }
    
    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #dc2626;
    }
    
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        display: block;
    }
    
    .form-text {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
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
    
    .char-counter {
        font-size: 0.875rem;
        color: #6b7280;
        text-align: right;
        margin-top: 0.5rem;
    }
    
    .char-counter.warning {
        color: #f59e0b;
    }
    
    .char-counter.danger {
        color: #dc2626;
    }
    
    .file-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f9fafb;
    }
    
    .file-upload-area:hover {
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .file-upload-area.active {
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .file-upload-icon {
        font-size: 3rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }
    
    .file-info {
        font-size: 0.9rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }
    
    .file-accepted {
        color: #10b981;
    }
    /* .footer-premium{
        margin-top:0 !important;
    } */
    @media (max-width: 768px) {
        .card-header-gradient {
            padding: 1.5rem;
        }
        
        .job-title-badge {
            margin-left: 0;
            margin-top: 0.5rem;
            display: inline-block;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="apply-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
               <!-- Application Tips -->
                <div class="mt-4">
                    <div class="alert alert-light border" role="alert">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-lightbulb text-warning me-2"></i>Tips for a great application:
                        </h6>
                        <ul class="mb-0">
                            <li>Address the hiring manager by name if possible</li>
                            <li>Highlight relevant experience from the job description</li>
                            <li>Keep it concise but comprehensive (300-500 words)</li>
                            <li>Proofread for spelling and grammar errors</li>
                            <li>Express enthusiasm for the role and company</li>
                        </ul>
                    </div>
                </div>
                <div class="application-card">
                    
                    <!-- Card Header -->
                    <div class="card-header-gradient text-white">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-2">Apply for Position</h3>
                                <h1 class="h4 mb-0">{{ $job->job_title }}</h1>
                            </div>
                            <span class="job-title-badge">
                                <i class="fas fa-briefcase me-1"></i> {{ $job->industry }}
                            </span>
                        </div>
                        <div class="mt-3">
                            <small class="opacity-90">
                                <i class="fas fa-building me-1"></i>{{ $job->company_name ?? 'Company not specified' }} 
                                • 
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $job->work_location }}
                            </small>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('jobs.apply', $job->id) }}" method="POST" enctype="multipart/form-data" id="applyForm">
                            @csrf
                            
                            <!-- Cover Letter Field -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-edit me-2"></i>Cover Letter *
                                </label>
                                <textarea 
                                    class="form-control {{ $errors->has('cover_letter') ? 'is-invalid' : '' }}" 
                                    name="cover_letter" 
                                    rows="8" 
                                    required
                                    minlength="50"
                                    maxlength="2000"
                                    placeholder="Write your cover letter here. Explain why you're a good fit for this position..."
                                    id="coverLetter"
                                >{{ old('cover_letter') }}</textarea>
                                
                                @if($errors->has('cover_letter'))
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('cover_letter') }}
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>Minimum 50 characters
                                    </div>
                                    <div class="char-counter" id="charCounter">
                                        0/2000 characters
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Resume Upload -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-file-upload me-2"></i>Upload Resume (Optional)
                                </label>
                                
                                <div class="file-upload-area" id="fileUploadArea">
                                    <div class="file-upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <h5 class="mb-2">Drop your resume here or click to browse</h5>
                                    <p class="text-muted mb-2">Supported formats: PDF, DOC, DOCX</p>
                                    <p class="file-info" id="fileInfo">No file selected</p>
                                    <input type="file" class="d-none" name="resume" accept=".pdf,.doc,.docx" id="resumeInput">
                                </div>
                                
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Adding a resume increases your chances by 40%
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center pt-4 border-top">
                                <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-secondary mb-2 mb-md-0">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Job
                                </a>
                                
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Application
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
    // Character counter for cover letter
    const coverLetter = document.getElementById('coverLetter');
    const charCounter = document.getElementById('charCounter');
    
    if (coverLetter && charCounter) {
        coverLetter.addEventListener('input', function() {
            const length = this.value.length;
            charCounter.textContent = `${length}/2000 characters`;
            
            // Update color based on length
            charCounter.className = 'char-counter';
            if (length < 50) {
                charCounter.classList.add('danger');
            } else if (length > 1500) {
                charCounter.classList.add('warning');
            }
        });
        
        // Trigger initial count
        coverLetter.dispatchEvent(new Event('input'));
    }
    
    // File upload handling
    const fileUploadArea = document.getElementById('fileUploadArea');
    const resumeInput = document.getElementById('resumeInput');
    const fileInfo = document.getElementById('fileInfo');
    
    if (fileUploadArea && resumeInput && fileInfo) {
        // Click on area triggers file input
        fileUploadArea.addEventListener('click', function() {
            resumeInput.click();
        });
        
        // Drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            fileUploadArea.classList.add('active');
        }
        
        function unhighlight() {
            fileUploadArea.classList.remove('active');
        }
        
        // Handle file drop
        fileUploadArea.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        });
        
        // Handle file selection
        resumeInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                
                if (validTypes.includes(file.type)) {
                    fileInfo.innerHTML = `
                        <i class="fas fa-check-circle me-1 file-accepted"></i>
                        ${file.name} (${(file.size / 1024).toFixed(1)} KB)
                    `;
                    fileUploadArea.classList.add('active');
                } else {
                    fileInfo.innerHTML = `
                        <i class="fas fa-times-circle me-1 text-danger"></i>
                        Invalid file type. Please upload PDF or Word documents.
                    `;
                    resumeInput.value = '';
                }
            }
        }
    }
    
    // Form submission handling
    const applyForm = document.getElementById('applyForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (applyForm && submitBtn) {
        applyForm.addEventListener('submit', function(e) {
            const coverLetterText = document.getElementById('coverLetter').value;
            
            if (coverLetterText.length < 50) {
                e.preventDefault();
                alert('Please write a cover letter with at least 50 characters.');
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            submitBtn.disabled = true;
        });
    }
    
    // AJAX form submission (optional)
    @if(session('success'))
    const successMessage = "{{ session('success') }}";
    if (successMessage) {
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alertDiv.style.zIndex = '1060';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${successMessage}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Close window after delay
        setTimeout(() => {
            if (window.opener) {
                window.close();
            } else {
                window.location.href = "{{ route('jobs.show', $job->id) }}";
            }
        }, 3000);
    }
    @endif
});
</script>
@endpush