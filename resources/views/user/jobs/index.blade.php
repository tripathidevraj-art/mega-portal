@extends('layouts.app')

@section('title', 'Browse Jobs')
@section('header', 'Find Your Dream Job')

@push('styles')
<style>
    .jobs-container {
        min-height: calc(100vh - 200px);
        padding: 2rem 0;
    }
    
    .filter-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
    }
    
    .input-group-text {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        border: none;
        color: white;
    }
    
    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0051ff;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    .view-toggle {
        background: white;
        border-radius: 10px;
        padding: 0.25rem;
        display: inline-flex;
        border: 2px solid #e5e7eb;
    }
    
    .view-toggle .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .view-toggle .btn.active {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        color: white;
        border-color: transparent;
    }
    
    /* Grid View Cards */
    .job-card-grid {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }
    
    .job-card-grid:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        border-color: #0051ff;
    }
    
    .job-card-grid .card-header {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        color: white;
        padding: 1.25rem;
        border-bottom: none;
    }
    
    .job-card-grid .card-body {
        padding: 1.5rem;
    }
    
    .job-badge {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .job-type-badge {
        background: rgba(16, 185, 129, 0.1);
        color: #0051ff;
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .job-meta {
        color: #6b7280;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .job-meta i {
        color: #0051ff;
        width: 16px;
    }
    a{
        text-decoration:none;
    }
    
    /* List View Items */
    .job-item-list {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .job-item-list:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border-color: #0051ff;
    }
    
    .job-title {
        color: #111827;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .job-title:hover {
        color: #0051ff;
    }
    
    .btn-view-job {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-view-job:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }
    
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        border: 2px dashed #e5e7eb;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }
    
    /* Pagination */
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        border-color: transparent;
    }
    
    .pagination .page-link {
        color: #0051ff;
        border: 1px solid #e5e7eb;
        margin: 0 0.25rem;
        border-radius: 8px;
    }
    
    .pagination .page-link:hover {
        background: #f3f4f6;
        border-color: #0051ff;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .jobs-container {
            padding: 1rem;
        }
        
        .filter-card .row {
            gap: 1rem;
        }
        
        .job-item-list {
            padding: 1.25rem;
        }
        
        .view-toggle {
            margin-top: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="jobs-container">
    <div class="container">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="display-6 fw-bold mb-2">Browse Available Jobs</h1>
            <p class="text-muted mb-0">Find the perfect opportunity that matches your skills and ambitions</p>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" id="job-filter-form">
                <input type="hidden" name="view" value="{{ $view }}">
                
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label for="search" class="form-label visually-hidden">Search Jobs</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="form-control" 
                                   placeholder="Job title, company, keywords...">
                        </div>
                    </div>

                    <!-- Job Type -->
                    <div class="col-md-2">
                        <label for="job_type" class="form-label visually-hidden">Job Type</label>
                        <select name="job_type" class="form-select" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            @foreach($jobTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('job_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Industry -->
                    <div class="col-md-2">
                        <label for="industry" class="form-label visually-hidden">Industry</label>
                        <select name="industry" class="form-select" onchange="this.form.submit()">
                            <option value="">All Industries</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry }}" {{ request('industry') == $industry ? 'selected' : '' }}>
                                    {{ $industry }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div class="col-md-2">
                        <label for="location" class="form-label visually-hidden">Location</label>
                        <select name="location" class="form-select" onchange="this.form.submit()">
                            <option value="">All Locations</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="col-md-2">
                        <label for="sort" class="form-label visually-hidden">Sort By</label>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                <i class="fas fa-clock me-1"></i> Latest
                            </option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                Oldest
                            </option>
                            <option value="deadline_soon" {{ request('sort') == 'deadline_soon' ? 'selected' : '' }}>
                                Deadline Soon
                            </option>
                            <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>
                                Salary: High to Low
                            </option>
                            <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>
                                Salary: Low to High
                            </option>
                        </select>
                    </div>
                </div>
                
                <!-- Active Filters -->
                @if(request()->hasAny(['search', 'job_type', 'industry', 'location']))
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <small class="text-muted me-2">Active filters:</small>
                        @if(request('search'))
                        <span class="badge bg-light text-dark px-3 py-1">
                            <i class="fas fa-search me-1"></i>{{ request('search') }}
                            <a href="{{ removeFilter('search') }}" class="ms-2 text-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif
                        @if(request('job_type'))
                        <span class="badge bg-light text-dark px-3 py-1">
                            <i class="fas fa-briefcase me-1"></i>{{ $jobTypes[request('job_type')] }}
                            <a href="{{ removeFilter('job_type') }}" class="ms-2 text-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif
                        @if(request('industry'))
                        <span class="badge bg-light text-dark px-3 py-1">
                            <i class="fas fa-industry me-1"></i>{{ request('industry') }}
                            <a href="{{ removeFilter('industry') }}" class="ms-2 text-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif
                        @if(request('location'))
                        <span class="badge bg-light text-dark px-3 py-1">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ request('location') }}
                            <a href="{{ removeFilter('location') }}" class="ms-2 text-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif
                        <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-times me-1"></i>Clear All
                        </a>
                    </div>
                </div>
                @endif
            </form>
        </div>

        <!-- Results Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0 fw-bold">{{ $jobs->total() }} Job{{ $jobs->total() != 1 ? 's' : '' }} Available</h3>
                @if($jobs->total() > 0)
                <p class="text-muted mb-0">Showing {{ $jobs->firstItem() }}-{{ $jobs->lastItem() }} of {{ $jobs->total() }}</p>
                @endif
            </div>
            
            <!-- View Toggle -->
            <div class="view-toggle">
                <a href="{{ route('jobs.index', array_merge(request()->query(), ['view' => 'grid'])) }}" 
                   class="btn {{ $view === 'grid' ? 'active' : 'btn-light' }}">
                    <i class="fas fa-th-large me-1"></i> Grid
                </a>
                <a href="{{ route('jobs.index', array_merge(request()->query(), ['view' => 'list'])) }}" 
                   class="btn {{ $view === 'list' ? 'active' : 'btn-light' }}">
                    <i class="fas fa-list me-1"></i> List
                </a>
            </div>
        </div>

        <!-- Jobs List/Grid -->
        @if($jobs->count() > 0)
            @if($view === 'list')
                <!-- LIST VIEW -->
                <div class="mb-4">
                    @foreach($jobs as $job)
                    <div class="job-item-list">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <h4 class="mb-2">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="job-title">
                                            {{ $job->job_title }}
                                        </a>
                                    </h4>
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                        <!-- Company -->
                                        <div class="job-meta">
                                            <i class="fas fa-building"></i>
                                            <span>{{ $job->company_name ?? $job->user?->company_name ?? 'Not specified' }}</span>
                                        </div>
                                        
                                        <!-- Industry -->
                                        <div class="job-meta">
                                            <i class="fas fa-industry"></i>
                                            <span>{{ $job->industry }}</span>
                                        </div>
                                        
                                        <!-- Location -->
                                        <div class="job-meta">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $job->work_location }}</span>
                                        </div>
                                        
                                        <!-- Job Type -->
                                        <span class="job-type-badge">
                                            {{ str_replace('_', ' ', ucfirst($job->job_type)) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Salary & Applications -->
                                    <div class="d-flex flex-wrap align-items-center gap-4">
                                        <div class="job-meta">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span class="fw-medium">{{ $job->salary_range }}</span>
                                        </div>
                                        <div class="job-meta">
                                            <i class="fas fa-users"></i>
                                            <span>{{ $job->applications_count }} applicant{{ $job->applications_count != 1 ? 's' : '' }}</span>
                                        </div>
                                        <div class="job-meta">
                                            <i class="fas fa-clock"></i>
                                            <span>Expires: {{ $job->app_deadline->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 text-lg-end">
                                <a href="{{ route('jobs.show', $job->id) }}" class="btn-view-job">
                                    <i class="fas fa-arrow-right me-1"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- GRID VIEW -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
                    @foreach($jobs as $job)
                    <div class="col">
                        <div class="job-card-grid">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="job-badge">{{ $job->industry }}</span>
                                        <h5 class="mt-2 mb-1">{{ Str::limit($job->job_title, 40) }}</h5>
                                        <p class="mb-0 opacity-75">
                                            <i class="fas fa-building me-1"></i>
                                            {{ $job->company_name ?? $job->user?->company_name ?? 'Company' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <!-- Job Type -->
                                <div class="mb-3">
                                    <span class="job-type-badge mb-2 d-inline-block">
                                        {{ str_replace('_', ' ', ucfirst($job->job_type)) }}
                                    </span>
                                </div>
                                
                                <!-- Job Description -->
                                <p class="text-muted mb-3" style="font-size: 0.9rem; line-height: 1.5;">
                                    {{ Str::limit($job->job_description, 120) }}
                                </p>
                                
                                <!-- Meta Info -->
                                <div class="space-y-2 mb-4">
                                    <div class="job-meta">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $job->work_location }}</span>
                                    </div>
                                    <div class="job-meta">
                                        <i class="fas fa-dollar-sign"></i>
                                        <span class="fw-medium">{{ $job->salary_range }}</span>
                                    </div>
                                    <div class="job-meta">
                                        <i class="fas fa-clock"></i>
                                        <span>Expires: {{ $job->app_deadline->format('M d, Y') }}</span>
                                    </div>
                                    <div class="job-meta">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $job->applications_count }} applicant{{ $job->applications_count != 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Action Button -->
                                <div class="text-center">
                                    <a href="{{ route('jobs.show', $job->id) }}" class="btn-view-job w-100">
                                        <i class="fas fa-eye me-1"></i> View Job Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            @if($jobs->hasPages())
            <div class="mt-5">
                <nav aria-label="Jobs pagination">
                    <ul class="pagination justify-content-center mb-0">
                        {{ $jobs->appends(['view' => $view])->onEachSide(1)->links() }}
                    </ul>
                </nav>
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="mb-3">No Jobs Found</h3>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'job_type', 'industry', 'location']))
                        Try adjusting your filters or search terms
                    @else
                        No job postings available at the moment. Check back soon!
                    @endif
                </p>
                @if(request()->hasAny(['search', 'job_type', 'industry', 'location']))
                <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                    <i class="fas fa-times me-2"></i> Clear All Filters
                </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Helper function to remove filters
function removeFilter(param) {
    const url = new URL(window.location.href);
    url.searchParams.delete(param);
    return url.toString();
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Make list view items clickable
    document.querySelectorAll('.job-item-list').forEach(item => {
        item.style.cursor = 'pointer';
        item.addEventListener('click', function(e) {
            if (!e.target.closest('a, button')) {
                const link = this.querySelector('a.job-title');
                if (link) {
                    window.location.href = link.href;
                }
            }
        });
    });
    
    // Make grid view cards clickable
    document.querySelectorAll('.job-card-grid').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function(e) {
            if (!e.target.closest('a, button')) {
                const link = this.querySelector('a.btn-view-job');
                if (link) {
                    window.location.href = link.href;
                }
            }
        });
    });
});
</script>
@endpush