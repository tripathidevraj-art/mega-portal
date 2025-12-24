@extends('layouts.app')

@section('title', 'Registration Successful')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow text-center">
            <div class="card-body py-5">
                <div class="mb-4">
                    <i class="fas fa-envelope-circle-check fa-5x text-success"></i>
                </div>
                
                <h2 class="mb-3">Registration Successful!</h2>
                
                <p class="text-muted mb-4">
                    Thank you for registering. We have sent a verification email to your registered email address.
                    Please check your inbox and click the verification link to activate your account.
                </p>
                
                
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt me-2"></i>Go to Login
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Go to Home
                    </a>
                </div>
                
                <div class="mt-5">
                    <p class="text-muted mb-2">Didn't receive the email?</p>
                    <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ old('email') }}">
                        <button type="submit" class="btn btn-link">
                            <i class="fas fa-redo me-1"></i>Resend Verification Email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection