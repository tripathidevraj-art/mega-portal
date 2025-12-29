<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Job & Offer Portal')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-briefcase"></i> Job & Promos Portal
                 <!-- <img src="https://innoricsystems.in/uploads/ngo-logo.png" class="" alt="Logo"> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('jobs.index') }}">Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('offers.index') }}">Promos</a>
                    </li>
                  @auth
                        {{-- Super Admin --}}
                        @if(auth()->user()->isSuperAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="superadminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-crown"></i> Super Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('superadmin.admins.index') }}">
                                            Manage Admins
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Admin --}}
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog"></i> Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.approval-queue') }}">Approval Queue</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.expired-content') }}">Expired Content</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users-management') }}">Users Management</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.user-logs') }}">User Logs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.user-analytics') }}">Analytics</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}">My Profile</a></li>
                                </ul>
                            </li>

                        {{-- Regular User --}}
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> My Account
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.jobs.my-jobs') }}">My Jobs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.offers.my-offers') }}">My Offers</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth

                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                                @if(auth()->user()->isSuspended())
                                    <span class="badge bg-danger">Suspended</span>
                                @endif
                            </span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <!-- Toast Notifications -->
            <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1056;"></div>
            
            <!-- Page Header -->
            @if(isset($header))
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3">{{ $header }}</h1>
                        @if(isset($subheader))
                            <p class="text-muted">{{ $subheader }}</p>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Job & Offer Portal</h5>
                    <p>Find jobs and product offers in one place.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Job & Offer Portal. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>