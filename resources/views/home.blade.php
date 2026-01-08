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
