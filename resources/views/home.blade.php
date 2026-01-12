@extends('layouts.app')

@section('content')

{{-- ================= PREMIUM AUTH DASHBOARD ================= --}}
@auth
<!-- Hero Welcome Section -->
<div class="hero-welcome py-5 mb-4" style="background: linear-gradient(135deg, #2a2b31 0%, #5a78db 100%);">
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

<div class="container main-container">
    <div class="row">
        <!-- Left Column - Main Content -->
        <div class="col-lg-8">
            <!-- Weather & News Grid -->
            <div class="row mb-4">
                <div class="col-md-6 mb-4">
                    <!-- Weather Widget -->
<!-- Weather Widget -->
<div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
    <div class="card-header bg-gradient-primary text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-cloud-sun me-2"></i>Current Weather</h5>
            <button class="btn btn-sm btn-light rounded-circle" onclick="refreshWeather()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="text-center py-3">
            <div class="mb-3">
                <img id="weather-icon" src="" alt="Weather" class="weather-icon">
            </div>
            <h2 class="display-4 fw-bold mb-2" id="weather-temp">--°C</h2>
            <h5 id="weather-location" class="text-muted mb-3">Detecting location...</h5>
            <p class="mb-0" id="weather-desc">Fetching weather data...</p>
            <div class="mt-4">
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted d-block">Humidity</small>
                        <strong id="weather-humidity">--%</strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Wind</small>
                        <strong id="weather-wind">-- km/h</strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Feels Like</small>
                        <strong id="weather-feels">--°C</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-light">
        <small class="text-muted" id="weather-updated">Last updated: --</small>
    </div>
</div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <!-- Latest News Card -->
                    <div class="card border-0 shadow-lg rounded-4 h-100">
                        <div class="card-header bg-gradient-info text-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>Latest Updates</h5>
                                <span class="badge bg-white text-info">{{ $latestNews->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($latestNews->count())
                            <div class="list-group list-group-flush">
                                @foreach($latestNews->take(3) as $news)
                                <a href="{{ route('news.show', $news->id) }}" 
                                   class="list-group-item list-group-item-action border-0 py-3 px-4 hover-lift">
                                    <div class="d-flex align-items-start">
                                        @if($news->image)
                                        <div class="flex-shrink-0 me-3">
                                            <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                <img src="{{ asset('storage/'.$news->image) }}" 
                                                     class="img-cover" 
                                                     alt="{{ $news->title }}">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-dark">{{ Str::limit($news->title, 40) }}</h6>
                                            <small class="text-muted d-block">{{ Str::limit($news->excerpt, 60) }}</small>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>{{ $news->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No updates yet</p>
                            </div>
                            @endif
                        </div>
                        @if($latestNews->count())
                        <div class="card-footer bg-light text-center py-3">
                            <a href="{{ route('news.index') }}" class="btn btn-outline-info btn-sm">
                                View All News <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Jobs & Offers Tabs -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <ul class="nav nav-tabs nav-tabs-clean" id="jobsOffersTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobs" type="button">
                                <i class="fas fa-briefcase me-2"></i>Latest Jobs
                                <span class="badge bg-primary ms-2">{{ $latestJobs->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="offers-tab" data-bs-toggle="tab" data-bs-target="#offers" type="button">
                                <i class="fas fa-tag me-2"></i>Latest Offers
                                <span class="badge bg-success ms-2">{{ $latestOffers->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <!-- Jobs Tab -->
                        <div class="tab-pane fade show active" id="jobs">
                            @if($latestJobs->count())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Job Title</th>
                                            <th>Industry</th>
                                            <th>Location</th>
                                            <th>Expire On</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestJobs->take(5) as $job)
                                        <tr class="clickable-row" data-url="{{ $job->public_url }}">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-briefcase"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <strong class="d-block">{{ Str::limit($job->job_title, 30) }}</strong>
                                                        <small class="text-muted">by {{ $job->user->full_name ?? 'Anonymous' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info">{{ $job->industry }}</span>
                                            </td>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                {{ $job->work_location }}
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
                                                <button class="btn btn-sm btn-outline-primary" onclick="window.location='{{ $job->public_url }}'">
                                                    View
                                                </button>
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
                                <a href="{{ route('user.jobs.create') }}" class="btn btn-primary">Post a Job</a>
                            </div>
                            @endif
                        </div>

                        <!-- Offers Tab -->
                        <div class="tab-pane fade" id="offers">
                            @if($latestOffers->count())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Product</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Expire On</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestOffers->take(5) as $offer)
                                        <tr class="clickable-row" data-url="{{ $offer->public_url }}">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-tag"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <strong class="d-block">{{ Str::limit($offer->product_name, 25) }}</strong>
                                                        <small class="text-muted">by {{ $offer->user->full_name ?? 'Anonymous' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success">{{ $offer->category }}</span>
                                            </td>
                                            <td>
                                                @if($offer->discount > 0)
                                                <div>
                                                    <span class="text-decoration-line-through text-muted small">${{ number_format($offer->price, 2) }}</span>
                                                    <span class="text-success fw-bold ms-2">${{ number_format($offer->final_price, 2) }}</span>
                                                    <span class="badge bg-danger ms-2">{{ $offer->discount }}% OFF</span>
                                                </div>
                                                @else
                                                <span class="fw-bold">${{ number_format($offer->price, 2) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($offer->expiry_date->isToday())
                                                <span class="badge bg-danger">Today</span>
                                                @elseif($offer->expiry_date->isPast())
                                                <span class="badge bg-secondary">Expired</span>
                                                @else
                                                <span class="text-muted">{{ $offer->expiry_date->format('M d') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-success" onclick="window.location='{{ $offer->public_url }}'">
                                                    View
                                                </button>
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
                                <a href="{{ route('user.offers.create') }}" class="btn btn-success">Create Offer</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col">
                            @if($latestJobs->count() >= 5)
                            <a href="{{ route('jobs.index') }}" class="btn btn-link text-decoration-none">
                                View All Jobs <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            @endif
                        </div>
                        <div class="col text-end">
                            @if($latestOffers->count() >= 5)
                            <a href="{{ route('offers.index') }}" class="btn btn-link text-decoration-none">
                                View All Offers <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Top Performers Section -->
            <div class="sticky-sidebar" style="top: 20px;">
                <!-- Top Referrers -->
                @if($topReferrers->count())
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-gradient-warning text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>Top Referrers
                            <span class="float-end badge bg-white text-warning">Ranking</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($topReferrers as $index => $user)
                            <div class="list-group-item border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=f0ad4e&color=fff' }}" 
                                             class="rounded-circle" 
                                             width="45" 
                                             height="45" 
                                             alt="Avatar">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $user->full_name }}</h6>
                                        <small class="text-muted">{{ $user->referralsGiven()->count() }} referrals</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-star me-1"></i>{{ $user->total_points }} pts
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Top Job & Offer Posters -->
                <div class="row g-3 mb-4">
                    @if($topJobPosters->count())
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-gradient-primary text-white py-3">
                                <h6 class="mb-0">
                                    <i class="fas fa-briefcase me-2"></i>Top Job Posters
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                @foreach($topJobPosters->take(3) as $user)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=667eea&color=fff' }}" 
                                         class="rounded-circle me-3" 
                                         width="40" 
                                         height="40" 
                                         alt="Avatar">
                                    <div class="flex-grow-1">
                                        <small class="d-block fw-bold">{{ Str::limit($user->full_name, 15) }}</small>
                                        <small class="text-muted">{{ $user->job_count }} jobs posted</small>
                                    </div>
                                    <span class="badge bg-primary">{{ $user->job_count }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($topOfferPosters->count())
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-gradient-success text-white py-3">
                                <h6 class="mb-0">
                                    <i class="fas fa-tag me-2"></i>Top Offer Posters
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                @foreach($topOfferPosters->take(3) as $user)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=5cb85c&color=fff' }}" 
                                         class="rounded-circle me-3" 
                                         width="40" 
                                         height="40" 
                                         alt="Avatar">
                                    <div class="flex-grow-1">
                                        <small class="d-block fw-bold">{{ Str::limit($user->full_name, 15) }}</small>
                                        <small class="text-muted">{{ $user->offer_count }} offers posted</small>
                                    </div>
                                    <span class="badge bg-success">{{ $user->offer_count }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Admin Contacts -->
                @if($admins->count())
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-gradient-dark text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-shield-alt me-2"></i>Platform Admins
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            @foreach($admins as $admin)
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3 border rounded-3 hover-lift">
                                    <div class="position-relative">
                                        <img src="{{ $admin->profile_image ? asset('storage/'.$admin->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($admin->full_name).'&background=764ba2&color=fff' }}" 
                                             class="rounded-circle border border-2 border-white" 
                                             width="50" 
                                             height="50" 
                                             alt="Admin">
                                        <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-success" style="width: 12px; height: 12px;"></span>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 fw-bold">{{ $admin->full_name }}</h6>
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
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Bar -->
<div class="position-fixed bottom-0 end-0 m-4" style="z-index: 1030;">
    <div class="btn-group dropup">
        <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-lg px-4" data-bs-toggle="dropdown">
            <i class="fas fa-plus me-2"></i>Quick Post
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 p-2">
            <li>
                <a class="dropdown-item rounded-2 py-3" href="{{ route('user.jobs.create') }}">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Post a Job</h6>
                            <small class="text-muted">Hire talent for your business</small>
                        </div>
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item rounded-2 py-3" href="{{ route('user.offers.create') }}">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Create Offer</h6>
                            <small class="text-muted">Sell products or services</small>
                        </div>
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item rounded-2 py-3" href="{{ route('user.profile') }}">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Invite Friends</h6>
                            <small class="text-muted">Earn rewards with referrals</small>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
    </div>
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
    .main-container{
        padding:0 3rem !important;
    }
    .weather-icon {
        width: 80px;
        height: 80px;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
    }
    
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .nav-tabs-clean .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        font-weight: 600;
        color: #6c757d;
        background: transparent;
    }
    
    .nav-tabs-clean .nav-link.active {
        color: #667eea;
        border-bottom-color: #667eea;
        background: transparent;
    }
    
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    
    .clickable-row:hover {
        background-color: #f8f9fa;
    }
    
    .sticky-sidebar {
        position: sticky;
    }
    
    .img-cover {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f0ad4e 0%, #ff8c00 100%);
    }
    
    .bg-gradient-dark {
        background: linear-gradient(135deg, #343a40 0%, #495057 100%);
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .shadow-lg {
        box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
    }
    
    @media (max-width: 768px) {
        .sticky-sidebar {
            position: static;
        }
        
        .display-4 {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function fetchWeatherByCoordinates(lat, lon, source = 'geolocation') {
    const API_KEY = '36126aa68a5b4806a99103723261001'; 

    const locationEl = document.getElementById('weather-location');
    const tempEl = document.getElementById('weather-temp');
    const descEl = document.getElementById('weather-desc');
    const iconEl = document.getElementById('weather-icon');
    const updatedEl = document.getElementById('weather-updated');
    const humidityEl = document.getElementById('weather-humidity');
    const windEl = document.getElementById('weather-wind');
    const feelsEl = document.getElementById('weather-feels');

    if (!API_KEY) {
        descEl.textContent = 'Weather service not configured';
        return;
    }

    descEl.textContent = 'Loading weather...';

    fetch(`https://api.weatherapi.com/v1/current.json?key=${API_KEY}&q=${lat},${lon}&aqi=no`)
        .then(res => {
            if (!res.ok) throw new Error('Failed to fetch weather');
            return res.json();
        })
        .then(data => {
            const current = data.current;
            const loc = data.location;

            // Update UI
            tempEl.textContent = `${current.temp_c}°C`;
            feelsEl.textContent = `${current.feelslike_c}°C`;
            humidityEl.textContent = `${current.humidity}%`;
            windEl.textContent = `${Math.round(current.wind_kph)} km/h`;
            descEl.textContent = current.condition.text;
            locationEl.textContent = `${loc.name}, ${loc.country}`;

            iconEl.src = `https:${current.condition.icon}`;
            iconEl.alt = current.condition.text;

            updatedEl.textContent = `Last updated: ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;

            // Save coordinates & location name in localStorage for next visit
            localStorage.setItem('weather_location', JSON.stringify({
                lat,
                lon,
                name: `${loc.name}, ${loc.country}`,
                timestamp: Date.now()
            }));
        })
        .catch(err => {
            console.error('[WeatherAPI]', err);
            showError('Unable to load weather. Please try again.');
        });
}

function showError(message) {
    const locationEl = document.getElementById('weather-location');
    const tempEl = document.getElementById('weather-temp');
    const descEl = document.getElementById('weather-desc');
    const humidityEl = document.getElementById('weather-humidity');
    const windEl = document.getElementById('weather-wind');
    const feelsEl = document.getElementById('weather-feels');
    const updatedEl = document.getElementById('weather-updated');

    locationEl.textContent = 'Location unknown';
    tempEl.textContent = '--°C';
    feelsEl.textContent = '--°C';
    humidityEl.textContent = '--%';
    windEl.textContent = '-- km/h';
    descEl.textContent = message;
    updatedEl.textContent = 'Last updated: --';
}

function getLocationFromIP() {
    document.getElementById('weather-location').textContent = 'Detecting location...';
    
    fetch('https://ipapi.co/json/')
        .then(res => {
            if (!res.ok) throw new Error('IP geolocation failed');
            return res.json();
        })
        .then(data => {
            if (data.latitude && data.longitude) {
                fetchWeatherByCoordinates(data.latitude, data.longitude, 'ip');
            } else {
                throw new Error('No coordinates from IP');
            }
        })
        .catch(err => {
            console.warn('[IP Geolocation]', err);
            showError('Location detection failed. Enable location access for best results.');
        });
}

function requestUserGeolocation() {
    if (!navigator.geolocation) {
        getLocationFromIP();
        return;
    }

    // Show a friendly status before asking
    document.getElementById('weather-location').textContent = 'Requesting your location...';

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const { latitude, longitude } = position.coords;
            fetchWeatherByCoordinates(latitude, longitude, 'browser');
        },
        (error) => {
            console.warn('Geolocation denied or error:', error.message);
            // Fallback to IP
            getLocationFromIP();
        },
        {
            timeout: 10000,
            maximumAge: 300000 // Use cached position up to 5 minutes old
        }
    );
}

// Optional: Manual refresh
window.refreshWeather = function() {
    // Clear any cached location and re-request
    localStorage.removeItem('weather_location');
    initWeather();
};

function initWeather() {
    // Check if we have a recent cached location (valid for 24 hours)
    const cached = localStorage.getItem('weather_location');
    if (cached) {
        try {
            const loc = JSON.parse(cached);
            const age = Date.now() - loc.timestamp;
            if (age < 24 * 60 * 60 * 1000) { // < 24 hours old
                document.getElementById('weather-location').textContent = loc.name || 'Loading...';
                fetchWeatherByCoordinates(loc.lat, loc.lon, 'cache');
                return;
            }
        } catch (e) {
            localStorage.removeItem('weather_location');
        }
    }

    // No valid cache → request fresh location
    requestUserGeolocation();
}

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