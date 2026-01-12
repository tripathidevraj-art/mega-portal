@extends('layouts.app')

@section('title', 'Login')

@section('header', 'Welcome Back')
@section('subheader', 'Sign in to your account')

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
    .footer-premium{
        margin-top:0 !important;
    }
    /* Animated Background Container */
    .login-container {
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
    
    /* Card styling to work with background */
    .login-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(12px); /* Safari support */
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }
    
    /* Card header with gradient matching Vanta.js theme */
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #6B7280 50%, #667eea 100%);
        border-bottom: none;
        border-radius:1rem 1rem 0 0;
        padding: 2.5rem;
    }
    
    /* Form elements with coordinated colors */
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
    
    /* Primary button matching Vanta.js colors */
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
    
    /* Outline button */
    .btn-outline-primary {
        border: 2px solid #667eea;
        color: #667eea;
        background: transparent;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #6B7280 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Alert styling */
    .alert-danger {
        background: rgba(254, 226, 226, 0.9);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-left: 4px solid #dc2626;
    }
    
    /* Links */
    a.text-primary {
        color: #667eea !important;
        transition: color 0.3s ease;
    }
    
    a.text-primary:hover {
        color: #764ba2 !important;
        text-decoration: underline;
    }
    
    /* Checkbox */
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
    }
    
    /* Divider */
    .divider {
        position: relative;
        text-align: center;
        margin: 1.5rem 0;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #667eea, transparent);
    }
    
    .divider span {
        background: rgba(255, 255, 255, 0.95);
        padding: 0 1rem;
        position: relative;
        color: #667eea;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .login-container {
            padding: 1rem;
        }
        
        .card-header-gradient {
            padding: 1.5rem;
        }
        
        .login-card {
            margin: 1rem;
        }
    }
    
    @media (prefers-color-scheme: dark) {
        .login-card {
            background: rgba(30, 30, 46, 0.25); /* softer dark glass */
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .form-control {
            border-color: #334155;
        }

        .form-control:focus {
            background: rgba(156, 164, 177, 0.9);
        }

        .divider span {
            background: transparent; /* keep divider text readable */
            padding: 0 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="login-container d-flex align-items-center justify-content-center py-5">
    <!-- Vanta.js Background -->
    <div id="vanta-background"></div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="login-card">
                    <!-- Card Header -->
                    <div class="card-header-gradient text-white text-center py-4">
                        <i class="fas fa-sign-in-alt fa-3x mb-3"></i>
                        <h3 class="mb-2 fw-bold">Welcome Back</h3>
                        <p class="mb-0 opacity-90">Sign in to continue to your account</p>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body p-4 p-md-5">
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    <strong>Authentication Failed</strong>
                                    <p class="mb-0">{{ session('error') }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            <!-- Email -->
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
                                           name="email" value="{{ old('email') }}" 
                                           placeholder="Enter your email"
                                           required autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
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
                                           placeholder="Enter your password"
                                           required autocomplete="current-password">
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

                            <!-- Remember & Forgot -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" 
                                           id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                                
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                                    <i class="fas fa-key me-1"></i>Forgot Password?
                                </a>
                                @endif
                            </div>

                            <!-- Submit -->
                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </div>

                            <!-- Divider -->
                            <div class="divider mb-4">
                                <span>OR</span>
                            </div>

                            <!-- Register -->
                            <div class="text-center">
                                <p class="mb-3">Don't have an account?</p>
                                <a href="{{ route('register') }}" 
                                   class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-user-plus me-2"></i>Create New Account
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
    // Initialize Vanta.js background with colors matching theme
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
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    // Form submission loading state
    const form = document.getElementById('loginForm');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
                submitBtn.disabled = true;
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