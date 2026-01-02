@extends('layouts.app')

@section('title', 'Browse Jobs')
@section('header', 'Available Jobs')

@section('content')

{{-- Filter & Search Bar --}}
<div class="bg-light rounded-3 p-4 mb-4">
    <form method="GET" id="job-filter-form">
        {{-- Keep view mode --}}
        <input type="hidden" name="view" value="{{ $view }}">

        <div class="row g-3">
            {{-- Search --}}
            <div class="col-12 col-md-4">
                <label for="search" class="form-label visually-hidden">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="form-control" 
                           placeholder="Search jobs, industry, keywords...">
                           
                </div>
            </div>

            {{-- Job Type --}}
            <div class="col-6 col-md-2">
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

            {{-- Industry --}}
            <div class="col-6 col-md-2">
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

            {{-- Location --}}
            <div class="col-6 col-md-2">
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

            {{-- Sort --}}
            <div class="col-6 col-md-2">
                <label for="sort" class="form-label visually-hidden">Sort</label>
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="deadline_soon" {{ request('sort') == 'deadline_soon' ? 'selected' : '' }}>Deadline Soon</option>
                    <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Salary: High</option>
                    <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Salary: Low</option>
                </select>
            </div>
        </div>
    </form>
</div>

{{-- View Toggle Buttons (your existing ones) --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Available Jobs ({{ $jobs->total() }})</h2>
    <div class="btn-group" role="group">
        <a href="{{ route('jobs.index', array_merge(request()->query(), ['view' => 'grid'])) }}" 
           class="btn btn-outline-{{ $view === 'grid' ? 'primary' : 'secondary' }}">
            <i class="fas fa-th-large me-1"></i> Grid
        </a>
        <a href="{{ route('jobs.index', array_merge(request()->query(), ['view' => 'list'])) }}" 
           class="btn btn-outline-{{ $view === 'list' ? 'primary' : 'secondary' }}">
            <i class="fas fa-list me-1"></i> List
        </a>
    </div>
</div>
@if($jobs->count() > 0)
    @if($view === 'list')
        <!-- LIST VIEW -->
    <div class="space-y-4">
        @foreach($jobs as $job)
            <div class="border border-1 rounded-3 p-4 bg-white shadow-sm transition-hover">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">
                    <!-- Left: Title + Tags -->
                    <div class="w-100">
                        <h5 class="mb-2">
                            <a href="{{ route('jobs.show', $job->id) }}" class="text-decoration-none text-dark fw-bold hover-text-primary">
                                {{ $job->job_title }}
                            </a>
                        </h5>

                        <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                            <!-- Industry -->
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-building fa-fw me-1 text-gray-500"></i>
                                <span>{{ $job->industry }}</span>
                            </div>

                            <!-- Location -->
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-map-marker-alt fa-fw me-1 text-gray-500"></i>
                                <span>{{ $job->work_location }}</span>
                            </div>

                            <!-- Salary -->
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-dollar-sign fa-fw me-1 text-gray-500"></i>
                                <span>{{ $job->salary_range }}</span>
                            </div>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="fas fa-users fa-fw me-1"></i>
                                <span>{{ $job->applications_count }} applicant{{ $job->applications_count != 1 ? 's' : '' }}</span>
                            </div>
                            <!-- Job Type -->
                            <span class="badge bg-{{ $job->job_type == 'full_time' ? 'primary' : ($job->job_type == 'part_time' ? 'success' : 'info') }} rounded-pill px-2 py-1 fw-normal">
                                {{ str_replace('_', ' ', ucfirst($job->job_type)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Right: Deadline + Action -->
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 ms-md-auto">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="far fa-clock fa-fw me-1"></i>
                            <span>Expire On: {{ $job->app_deadline->format('M d, Y') }}</span>
                        </div>
                        <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-primary px-3">
                            <i class="fas fa-arrow-right me-1"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
        <!-- GRID VIEW (your existing code) -->
        <div class="row">
            @foreach($jobs as $job)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow job-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">{{ $job->job_title }}</h5>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-building me-1"></i>{{ $job->industry }}
                                    </p>
                                </div>
                                <span class="badge bg-{{ $job->job_type == 'full_time' ? 'primary' : ($job->job_type == 'part_time' ? 'success' : 'info') }}">
                                    {{ str_replace('_', ' ', ucfirst($job->job_type)) }}
                                </span>
                            </div>
                            
                            <p class="card-text text-muted mb-3">{{ Str::limit($job->job_description, 150) }}</p>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="mb-1"><i class="fas fa-map-marker-alt me-1"></i> Location</p>
                                    <small class="text-muted">{{ $job->work_location }}</small>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><i class="fas fa-money-bill-wave me-1"></i> Salary</p>
                                    <small class="text-muted">{{ $job->salary_range }}</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i> 
                                        {{ $job->applications_count }} applicant{{ $job->applications_count != 1 ? 's' : '' }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>Posted by: {{ $job->user?->full_name ?? 'Unknown' }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>Expire On: {{ $job->app_deadline->format('M d, Y') }}
                                    </small>
                                </div>
                                <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Pagination (works for both views) -->
    <div class="col-12 mt-4">
        <div class="d-flex justify-content-center">
            {{ $jobs->appends(['view' => $view])->links() }}
        </div>
    </div>

@else
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                <h4>No Jobs Available</h4>
                <p class="text-muted">Check back later for new job postings.</p>
            </div>
        </div>
    </div>
@endif

@endsection