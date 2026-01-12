@extends('layouts.app')

@section('title', 'Confirm Password')

@section('header', 'Confirm Your Password')
@section('subheader', 'For security, please re-enter your password')

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
    .confirm-container {
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
    .confirm-card {
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

    .confirm-card:hover {
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

    .btn-link-styled {
        display: inline-block;
        margin-top: 0.75rem;
        color: #667eea !important;
        text-decoration: none;
        transition: color 0.3s ease;
        font-weight: 500;
    }

    .btn-link-styled:hover {
        color: #764ba2 !important;
        text-decoration: underline;
    }

    .alert-info {
        background: rgba(239, 246, 255, 0.9);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-left: 4px solid #6366f1;
        color: #4338ca;
        padding: 1rem;
        border-radius: 0.5rem;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .confirm-container {
            padding: 1rem;
        }
        .card-header-gradient {
            padding: 1.5rem;
        }
        .confirm-card {
            margin: 1rem;
        }
    }

    @media (prefers-color-scheme: dark) {
        .confirm-card {
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
        .alert-info {
            background: rgba(23, 23, 37, 0.7);
            border-color: rgba(124, 58, 237, 0.3);
            color: #c4b5fd;
        }
    }
</style>
@endpush

@section('content')
<div class="confirm-container d-flex align-items-center justify-content-center py-5">
    <div id="vanta-background"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="confirm-card">
                    <div class="card-header-gradient text-white">
                        <i class="fas fa-shield-alt fa-3x mb-3"></i>
                        <h3 class="mb-2 fw-bold">Confirm Password</h3>
                        <p class="mb-0 opacity-90">Re-enter your password to continue</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('Please confirm your password before continuing.') }}
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <div>
                                        <strong>Authentication Failed</strong>
                                        @foreach ($errors->all() as $error)
                                            <p class="mb-0">{{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.confirm') }}" id="confirmForm">
                            @csrf

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           required autocomplete="current-password"
                                           placeholder="••••••••">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary py-3 fw-bold">
                                    <i class="fas fa-check-circle me-2"></i>Confirm Password
                                </button>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-center">
                                    <a href="{{ route('password.request') }}" class="btn-link-styled">
                                        <i class="fas fa-key me-1"></i>Forgot Your Password?
                                    </a>
                                </div>
                            @endif
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
    // Vanta.js background
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

    // Password toggle
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = toggleBtn.querySelector('i');
            if (type === 'text') {
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

    // Form loading state
    const form = document.getElementById('confirmForm');
    if (form) {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';
                btn.disabled = true;
            }
        });
    }

    // Focus password field
    if (passwordInput && !passwordInput.value.trim()) {
        passwordInput.focus();
    }
});
</script>
@endsection