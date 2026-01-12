@extends('layouts.app')

@section('title', 'Forgot Password')

@section('header', 'Forgot Your Password?')
@section('subheader', 'Enter your email to receive a reset link')

@push('styles')
<style>
    main.py-4 {
        padding: 0 !important;
    }
    main.py-4 div.container {
        max-width: none;
        margin: 0;
        padding: 0;
    }
    .footer-premium {
        margin-top: 0 !important;
    }

    /* Animated Background Container */
    .forgot-container {
        min-height: calc(100vh - 200px);
        position: relative;
        overflow: hidden;
    }

    #vanta-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    /* Card styling */
    .forgot-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .forgot-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #6B7280 50%, #667eea 100%);
        border-bottom: none;
        border-radius: 1rem 1rem 0 0;
        padding: 2.5rem;
        text-align: center;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }

    .input-group-text {
        background: rgba(102, 126, 234, 0.1);
        border: 2px solid #e2e8f0;
        border-right: none;
        color: #667eea;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #6B7280 100%);
        border: none;
        padding: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        background: linear-gradient(135deg, #6B7280 0%, #667eea 100%);
    }

    .alert-success {
        background: rgba(220, 255, 220, 0.9);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-left: 4px solid #22c55e;
        color: #166534;
    }

    @media (max-width: 768px) {
        .forgot-container {
            padding: 1rem;
        }
        .card-header-gradient {
            padding: 1.5rem;
        }
        .forgot-card {
            margin: 1rem;
        }
    }

    @media (prefers-color-scheme: dark) {
        .forgot-card {
            background: rgba(30, 30, 46, 0.25);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .form-control {
            border-color: #334155;
            background: rgba(255, 255, 255, 0.85);
        }
        .form-control:focus {
            background: rgba(156, 164, 177, 0.9);
        }
        .alert-success {
            background: rgba(30, 58, 30, 0.7);
            border-color: rgba(74, 222, 128, 0.3);
            color: #a7f3d0;
        }
    }
</style>
@endpush

@section('content')
<div class="forgot-container d-flex align-items-center justify-content-center py-5">
    <div id="vanta-background"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="forgot-card">
                    <div class="card-header-gradient text-white">
                        <i class="fas fa-paper-plane fa-3x mb-3"></i>
                        <h3 class="mb-2 fw-bold">Forgot Password?</h3>
                        <p class="mb-0 opacity-90">We’ll send you a secure reset link</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <div>{{ session('status') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <div>
                                        <strong>Oops!</strong>
                                        @foreach ($errors->all() as $error)
                                            <p class="mb-0">{{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required autocomplete="email" autofocus
                                           placeholder="your@email.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Vanta.js
    VANTA.NET({
        el: "#vanta-background",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        color: 0x667eea,
        backgroundColor: 0xf8fafc,
        points: 12.00,
        maxDistance: 22.00,
        spacing: 18.00
    });

    // Form loading state
    const form = document.getElementById('forgotForm');
    if (form) {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
                btn.disabled = true;
            }
        });
    }

    // Auto-focus email
    const emailField = document.getElementById('email');
    if (emailField && !emailField.value.trim()) {
        emailField.focus();
    }
});
</script>
@endsection