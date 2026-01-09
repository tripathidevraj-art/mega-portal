@extends('layouts.app')

@section('content')

{{-- ================= GUEST VIEW ================= --}}
@guest
<div class="bg-dark text-light py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Welcome to Job & Offer Portal 👋</h1>
        <p class="lead mt-3">
            Find jobs, post offers, and grow your career.
        </p>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">
                Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                Register
            </a>
        </div>
    </div>
</div>
@endguest


{{-- ================= AUTH VIEW ================= --}}
@auth
<!-- Latest News Section -->
<section class="py-4">
    <div class="container">
        <h3 class="mb-4">Latest Updates</h3>
        
        @if($latestNews->count())
            <div class="row g-3">
                @foreach($latestNews as $news)
                <div class="col-12">
                    <div class="border rounded p-3 bg-white shadow-sm hover-shadow" style="cursor: pointer;" onclick="window.location='{{ route('news.show', $news->id) }}'">
                        <div class="d-flex gap-3">
                            @if($news->image)
                                <div class="flex-shrink-0" style="width: 70px; height: 70px; overflow: hidden; border-radius: 8px;">
                                    <img src="{{ asset('storage/'.$news->image) }}" 
                                         class="w-100 h-100 object-fit-cover" 
                                         alt="{{ $news->title }}">
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1 text-dark">{{ $news->title }}</h5>
                                <p class="text-muted mb-1" style="font-size: 0.95rem; line-height: 1.4;">
                                    {{ Str::limit($news->excerpt, 100) }}
                                </p>
                                <small class="text-muted">{{ $news->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($latestNews->count() >= 5)
            <div class="text-center mt-4">
                <a href="{{ route('news.index') }}" class="btn btn-outline-primary">
                    View All News &rarr;
                </a>
            </div>
            @endif
        @else
            <p class="text-muted text-center">No news available at the moment.</p>
        @endif
    </div>
</section>
<!-- Top Referrers Section -->
@if($topReferrers->count())
<div class="card mb-5">
    <div class="card-header bg-gradient-primary text-white">
        <h4 class="mb-0 text-dark"><i class="fas fa-trophy me-2"></i> Top Referrers</h4>
    </div>
    <div class="card-body">
        <ol class="list-group list-group-numbered">
        @foreach($topReferrers as $user)
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name) }}" 
                         class="rounded-circle me-2" width="32" height="32">
                    <span class="fw-bold">{{ $user->full_name }}</span>
                </div>
                <span class="badge bg-success">{{ $user->total_points }} pts</span>
            </li>
        @endforeach
        </ol>
    </div>
</div>
@endif
<!-- Hero Section -->
<div class="bg-dark text-light py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">
            Welcome Back, {{ Auth::user()->full_name }} 🎉
        </h1>
        
        <p class="lead mt-3">
            Manage jobs, offers, and your profile in one place.
        </p>
        <a href="dashboard" class="btn btn-primary btn-lg mt-3">
            Explore Dashboard
        </a>
    </div>
</div>

@endauth

@endsection
