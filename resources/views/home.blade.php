@extends('layouts.app')

@section('content')

{{-- ================= AUTH VIEW ================= --}}
@auth
<!-- Hero Welcome Section -->
<div class="hero-welcome py-5 mb-4" style="background: linear-gradient(135deg, #312a2a 0%, #5a78db 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-wrapper me-3">
                        <img src="{{ Auth::user()->profile_image ? asset('storage/'.Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->full_name).'&background=667eea&color=fff&size=128' }}" 
                             class="rounded-circle border border-3 border-white shadow" 
                             width="70" 
                             height="70" 
                             alt="Profile">
                    </div>
                    <div>
                        <h1 class="text-white mb-1 fw-bold">Welcome back, {{ Auth::user()->full_name }}!</h1>
                        <p class="text-white-80 mb-0">Here's what's happening in your community today</p>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="bg-white-20 text-white rounded-3 p-3">
                            <small class="d-block opacity-75">Total Posts</small>
                            <h4 class="mb-0 fw-bold">{{ Auth::user()->jobPostings()->count() + Auth::user()->productOffers()->count() }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white-20 text-white rounded-3 p-3">
                            <small class="d-block opacity-75">Referrals</small>
                            <h4 class="mb-0 fw-bold">{{ Auth::user()->referralsGiven()->count() }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white-20 text-white rounded-3 p-3">
                            <small class="d-block opacity-75">Member Since</small>
                            <h4 class="mb-0 fw-bold">{{ Auth::user()->created_at->format('M Y') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white-20 text-white rounded-3 p-3">
                            <small class="d-block opacity-75">Activity Score</small>
                            <h4 class="mb-0 fw-bold">85%</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="text-center">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-container">
    <!-- Row 1: News (70%) + Weather (30%) with equal height -->
    <div class="row mb-4">
        <!-- News Column (70%) -->
<div class="col-lg-8 mb-4">
    <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>Latest Updates</h5>
                <span class="badge bg-light text-primary">{{ $latestNews->count() }}</span>
            </div>
        </div>
        <!-- Card body with subtle background -->
        <div class="card-body p-0 bg-light" style="height: calc(100% - 60px); overflow-y: auto;">
            @if($latestNews->count())
            <div class="list-group list-group-flush">
                @foreach($latestNews as $news)
                <a href="{{ route('news.show', $news->id) }}" 
                   class="list-group-item list-group-item-action border-0 py-3 px-4 hover-lift">
                    <div class="d-flex align-items-start">
                        @if($news->image)
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                <img src="{{ asset('storage/'.$news->image) }}" 
                                     class="img-cover w-100 h-100" 
                                     alt="{{ $news->title }}">
                            </div>
                        </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-dark">{{ Str::limit($news->title, 60) }}</h6>
                            <p class="text-muted mb-2" style="font-size: 0.9rem; line-height: 1.4;">
                                {{ Str::limit($news->excerpt, 100) }}
                            </p>
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>{{ $news->created_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-5 h-100 d-flex flex-column justify-content-center">
                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                <p class="text-muted">No updates yet</p>
            </div>
            @endif
        </div>

        @if($latestNews->count() >= 5)
        <div class="card-footer bg-light text-center py-3">
            <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-sm">
                View All News <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        @endif
    </div>
</div>
        
        <!-- Weather Column (30%) -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-cloud-sun me-2"></i>Current Weather</h5>
                        <button class="btn btn-sm btn-light rounded-circle" onclick="refreshWeatherWidget()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted" id="weather-status">Detecting your location...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Top Contributors Heading -->
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-trophy me-2 text-warning"></i>Top Contributors
            </h4>
            <p class="text-muted mb-0">Our most active community members</p>
        </div>
    </div>
<!-- Row 3: Top Contributors Cards (Podium Style) -->
<div class="row mb-5">
    @php
        $ranks = [
            'referrers' => $topReferrers->take(5),
            'jobs' => $topJobPosters->take(5),
            'offers' => $topOfferPosters->take(5)
        ];
        $colors = [
            'referrers' => ['#3b82f6', '#2563eb', '#60a5fa'], // amber/gold
            'jobs' => ['#3b82f6', '#2563eb', '#60a5fa'],      // blue
            'offers' => ['#3b82f6', '#2563eb', '#60a5fa']     // emerald
        ];
    @endphp

    <!-- Top Referrers -->
    @if($topReferrers->count())
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, {{ $colors['referrers'][0] }}, {{ $colors['referrers'][1] }});">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>Top Referrers
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($topReferrers->take(5) as $index => $user)
                        @php
                            $rank = $index + 1;
                            $isTop3 = $rank <= 3;
                            $bgColor = $isTop3 ? $colors['referrers'][$rank - 1] : '#f8f9fa';
                            $textColor = $isTop3 ? 'white' : 'dark';
                            $elevation = match($rank) {
                                1 => 'transform: translateY(-8px); z-index: 3; box-shadow: 0 6px 16px rgba(0,0,0,0.15);',
                                2 => 'transform: translateY(-4px); z-index: 2; box-shadow: 0 4px 12px rgba(0,0,0,0.1);',
                                3 => 'transform: translateY(-2px); z-index: 1;',
                                default => ''
                            };
                        @endphp
                        <div class="list-group-item border-0 py-3 px-4 position-relative"
                             style="{{ $elevation }} background-color: {{ $isTop3 ? 'transparent' : '#fff' }};">
                            <div class="d-flex align-items-center">
                                <!-- Avatar + Rank Badge -->
                                <div class="position-relative me-3">
                                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=f59e0b&color=fff' }}" 
                                         class="rounded-circle" 
                                         width="45" height="45" alt="Avatar">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                                          style="background-color: {{ $bgColor }}; color: {{ $textColor }}; min-width: 22px; height: 22px; font-size: 0.7rem; display: flex; align-items: center; justify-content: center;">
                                        {{ $rank }}
                                    </span>
                                </div>

                                <!-- Name & Stats -->
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ Str::limit($user->full_name, 20) }}</h6>
                                    <small class="text-muted">{{ $user->referralsGiven()->count() }} referrals</small>
                                </div>

                                <!-- Points -->
                                <div class="text-end">
                                    <span class="badge" style="background-color: rgba({{ hexdec(substr($bgColor, 1, 2)) }}, {{ hexdec(substr($bgColor, 3, 2)) }}, {{ hexdec(substr($bgColor, 5, 2)) }}, 0.15); color: {{ $bgColor }};">
                                        <i class="fas fa-star me-1"></i>{{ $user->total_points }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Top Job Posters -->
    @if($topJobPosters->count())
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, {{ $colors['jobs'][0] }}, {{ $colors['jobs'][1] }});">
                <h5 class="mb-0">
                    <i class="fas fa-briefcase me-2"></i>Top Job Posters
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($topJobPosters->take(5) as $index => $user)
                        @php
                            $rank = $index + 1;
                            $isTop3 = $rank <= 3;
                            $bgColor = $isTop3 ? $colors['jobs'][$rank - 1] : '#f8f9fa';
                            $textColor = $isTop3 ? 'white' : 'dark';
                            $elevation = match($rank) {
                                1 => 'transform: translateY(-8px); z-index: 3; box-shadow: 0 6px 16px rgba(0,0,0,0.15);',
                                2 => 'transform: translateY(-4px); z-index: 2; box-shadow: 0 4px 12px rgba(0,0,0,0.1);',
                                3 => 'transform: translateY(-2px); z-index: 1;',
                                default => ''
                            };
                        @endphp
                        <div class="list-group-item border-0 py-3 px-4 position-relative"
                             style="{{ $elevation }} background-color: {{ $isTop3 ? 'transparent' : '#fff' }};">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=3b82f6&color=fff' }}" 
                                     class="rounded-circle me-3" width="45" height="45" alt="Avatar">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ Str::limit($user->full_name, 20) }}</h6>
                                    <small class="text-muted">{{ $user->job_count }} jobs posted</small>
                                </div>
                                <span class="badge" style="background-color: rgba({{ hexdec(substr($bgColor, 1, 2)) }}, {{ hexdec(substr($bgColor, 3, 2)) }}, {{ hexdec(substr($bgColor, 5, 2)) }}, 0.15); color: {{ $bgColor }};">
                                    {{ $user->job_count }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Top Offer Posters -->
    @if($topOfferPosters->count())
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, {{ $colors['offers'][0] }}, {{ $colors['offers'][1] }});">
                <h5 class="mb-0">
                    <i class="fas fa-tag me-2"></i>Top Offer Posters
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($topOfferPosters->take(5) as $index => $user)
                        @php
                            $rank = $index + 1;
                            $isTop3 = $rank <= 3;
                            $bgColor = $isTop3 ? $colors['offers'][$rank - 1] : '#f8f9fa';
                            $textColor = $isTop3 ? 'white' : 'dark';
                            $elevation = match($rank) {
                                1 => 'transform: translateY(-8px); z-index: 3; box-shadow: 0 6px 16px rgba(0,0,0,0.15);',
                                2 => 'transform: translateY(-4px); z-index: 2; box-shadow: 0 4px 12px rgba(0,0,0,0.1);',
                                3 => 'transform: translateY(-2px); z-index: 1;',
                                default => ''
                            };
                        @endphp
                        <div class="list-group-item border-0 py-3 px-4 position-relative"
                             style="{{ $elevation }} background-color: {{ $isTop3 ? 'transparent' : '#fff' }};">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=10b981&color=fff' }}" 
                                     class="rounded-circle me-3" width="45" height="45" alt="Avatar">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ Str::limit($user->full_name, 20) }}</h6>
                                    <small class="text-muted">{{ $user->offer_count }} offers posted</small>
                                </div>
                                <span class="badge" style="background-color: rgba({{ hexdec(substr($bgColor, 1, 2)) }}, {{ hexdec(substr($bgColor, 3, 2)) }}, {{ hexdec(substr($bgColor, 5, 2)) }}, 0.15); color: {{ $bgColor }};">
                                    {{ $user->offer_count }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

    <!-- Row 4: Latest Jobs & Offers -->
    <div class="row">
        <!-- Latest Jobs -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Latest Job Postings</h5>
                        <span class="badge bg-light text-success">{{ $latestJobs->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($latestJobs->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Job Title</th>
                                    <th>Industry</th>
                                    <th>Deadline</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestJobs->take(6) as $job)
                                <tr>
                                    <td class="ps-4">
                                        <strong class="d-block">{{ Str::limit($job->job_title, 25) }}</strong>
                                        <small class="text-muted">{{ $job->work_location }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $job->industry }}</span>
                                    </td>
                                    <td>
                                        @if($job->app_deadline->isToday())
                                        <span class="badge bg-danger">Today</span>
                                        @elseif($job->app_deadline->isPast())
                                        <span class="badge bg-secondary">Expired</span>
                                        @else
                                        <span class="text-muted">{{ $job->app_deadline->format('M d') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ $job->public_url }}" class="btn btn-sm btn-outline-success">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No active job postings</p>
                        <a href="{{ route('user.jobs.create') }}" class="btn btn-success btn-sm">Post a Job</a>
                    </div>
                    @endif
                </div>
                @if($latestJobs->count() >= 6)
                <div class="card-footer bg-light text-center py-3">
                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-success btn-sm">
                        View All Jobs <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Latest Offers -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-tag me-2"></i>Latest Product Offers</h5>
                        <span class="badge bg-light text-success">{{ $latestOffers->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($latestOffers->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestOffers->take(6) as $offer)
                                <tr>
                                    <td class="ps-4">
                                        <strong class="d-block">{{ Str::limit($offer->product_name, 25) }}</strong>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $offer->user->full_name ?? 'Anonymous' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $offer->category }}</span>
                                    </td>
                                    <td>
                                        @if($offer->discount > 0)
                                        <span class="text-decoration-line-through text-muted small d-block">${{ number_format($offer->price, 2) }}</span>
                                        <span class="text-success fw-bold">${{ number_format($offer->final_price, 2) }}</span>
                                        @else
                                        <span class="fw-bold">${{ number_format($offer->price, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ $offer->public_url }}" class="btn btn-sm btn-outline-success">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-tag fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No active offers</p>
                        <a href="{{ route('user.offers.create') }}" class="btn btn-success btn-sm">Create Offer</a>
                    </div>
                    @endif
                </div>
                @if($latestOffers->count() >= 6)
                <div class="card-footer bg-light text-center py-3">
                    <a href="{{ route('offers.index') }}" class="btn btn-outline-success btn-sm">
                        View All Offers <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Row 5: Know Your Admins -->
    @if($admins->count())
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Know Your Admins
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($admins as $admin)
                        <div class="col-md-6 col-lg-4">
                            <div class="d-flex align-items-start p-3 border rounded hover-lift">
                                <img src="{{ $admin->profile_image ? asset('storage/'.$admin->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($admin->full_name).'&background=764ba2&color=fff' }}" 
                                     class="rounded-circle me-3" 
                                     width="60" 
                                     height="60" 
                                     alt="Admin">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $admin->full_name }}</h6>
                                    @if($admin->designation)
                                    <small class="text-muted d-block">{{ $admin->designation }}</small>
                                    @endif
                                    @if($admin->company_name)
                                    <small class="text-muted">
                                        <i class="fas fa-building me-1"></i>{{ $admin->company_name }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endauth

@endsection

@push('styles')
<style>
        main.py-4{
        padding:0 !important;
    }
    main.py-4 div.container{
        max-width:none;
        margin:0;
        padding:0;
    }
    .section-container{
        padding: 0 3rem;
    }
    .hero-welcome {
        position: relative;
        overflow: hidden;
    }
    .hero-welcome .container{
        padding:0 3rem !important;
    }
    .hero-welcome::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,208C1248,224,1344,192,1392,176L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        background-position: bottom;
    }
    .scheme_43 .weatherapi-weather-cover{
        border-radius:0 !important;
    }
    .shm_4 .weatherapi-weather-more-weather-link{
        display:none !important;
    }
    .hero-welcome a {
    position: relative;
    z-index: 5;
    }
    .bg-white-20 {
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }
    
    .text-white-80 {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .hover-lift {
        transition: all 0.2s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-2px);
        background-color: #f8f9fa;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-top: none;
        font-size: 0.9rem;
    }
    
    .table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    .rounded-3 {
        border-radius: 12px !important;
    }
    
    .shadow-sm {
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .img-cover {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .weather-icon {
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
    }
    
    /* Equal height columns */
    .h-100 {
        height: 100% !important;
    }
    
    .d-flex.flex-column.justify-content-center {
        min-height: 300px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-welcome .row {
            text-align: center;
        }
        
        .hero-welcome .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .avatar-wrapper {
            margin-bottom: 1rem;
            margin-right: 0 !important;
        }
        
        .display-5 {
            font-size: 1.75rem;
        }
        
        .col-lg-4, .col-md-4 {
            margin-bottom: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.85rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .card-header h5 {
            font-size: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Make table rows clickable
document.addEventListener('DOMContentLoaded', function() {
    // Jobs table row click
    document.querySelectorAll('#latestJobsTable tbody tr').forEach(row => {
        const link = row.querySelector('a.btn-outline-info');
        if (link) {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function(e) {
                if (!e.target.closest('a, button')) {
                    window.location.href = link.href;
                }
            });
        }
    });
    
    // Offers table row click
    document.querySelectorAll('#latestOffersTable tbody tr').forEach(row => {
        const link = row.querySelector('a.btn-outline-success');
        if (link) {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function(e) {
                if (!e.target.closest('a, button')) {
                    window.location.href = link.href;
                }
            });
        }
    });
});
</script>
@endpush

@push('scripts')
<script>
const WEATHER_API_KEY = '36126aa68a5b4806a99103723261001';

function loadWeatherWidget(locId) {
    const container = document.getElementById('weatherapi-weather-widget-3');
    if (!container) {
        // Create container if missing
        const col = document.querySelector('.col-lg-4.mb-4');
        if (col) {
            col.innerHTML = `
                <div>
                    <div class="card-header bg-primary text-white py-3 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-cloud-sun me-2"></i>Current Weather</h5>
                            <button class="btn btn-sm btn-light rounded-circle" onclick="refreshWeatherWidget()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="weatherapi-weather-widget-3"></div>
                    </div>
                </div>
            `;
        }
    }

    // Clear previous widget
    document.getElementById('weatherapi-weather-widget-3').innerHTML = '';

    // Build widget URL with dynamic locId
    const scriptUrl = `https://www.weatherapi.com/weather/widget.ashx?loc=${locId}&wid=3&tu=1&div=weatherapi-weather-widget-3`;

    // Remove old script if exists
    const oldScript = document.getElementById('weather-widget-script');
    if (oldScript) oldScript.remove();

    // Inject new script
    const script = document.createElement('script');
    script.id = 'weather-widget-script';
    script.src = scriptUrl;
    script.async = true;
    script.onerror = () => {
        document.getElementById('weather-status').textContent = 'Failed to load weather';
    };
    document.head.appendChild(script);
}

function getLocationAndLoadWidget() {
    const statusEl = document.getElementById('weather-status');
    if (statusEl) statusEl.textContent = 'Getting your location...';

    if (!navigator.geolocation) {
        if (statusEl) statusEl.textContent = 'Geolocation not supported';
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            const { latitude, longitude } = position.coords;

            if (statusEl) statusEl.textContent = 'Finding your city...';

            try {
                // Use WeatherAPI's search endpoint to get loc ID from lat,lng
                const searchRes = await fetch(
                    `https://api.weatherapi.com/v1/search.json?key=${WEATHER_API_KEY}&q=${latitude},${longitude}`
                );
                const locations = await searchRes.json();

                if (locations && locations[0] && locations[0].id) {
                    if (statusEl) statusEl.textContent = 'Loading weather...';
                    loadWeatherWidget(locations[0].id); // e.g., 3333137
                } else {
                    throw new Error('No location found');
                }
            } catch (err) {
                console.error('Location lookup failed:', err);
                if (statusEl) statusEl.textContent = 'City not found';
            }
        },
        (error) => {
            let msg = 'Location access denied';
            if (error.code === 2) msg = 'Location unavailable';
            if (statusEl) statusEl.textContent = msg;
        },
        { timeout: 10000, maximumAge: 300000 }
    );
}

window.refreshWeatherWidget = () => {
    // Clear and restart
    const container = document.getElementById('weatherapi-weather-widget-3');
    if (container) container.innerHTML = '';
    const statusEl = document.getElementById('weather-status');
    if (statusEl) statusEl.textContent = 'Refreshing...';
    setTimeout(getLocationAndLoadWidget, 300);
};

// Start on page load
document.addEventListener('DOMContentLoaded', () => {
    // Only run if we're in auth view and weather section exists
    if (document.querySelector('.col-lg-4.mb-4') || document.getElementById('weatherapi-weather-widget-3')) {
        getLocationAndLoadWidget();
    }
});

// Start on page load
document.addEventListener('DOMContentLoaded', () => {
    if (
        document.getElementById('weather-temp') &&
        document.getElementById('weather-location')
    ) {
        initWeather();
    }
});
</script>
@endpush