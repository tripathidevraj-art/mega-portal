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
