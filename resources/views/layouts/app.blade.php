<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Job & Promos Portal')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #6b7280;
            --light-bg: #f8fafc;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            background-color: #ffffff;
        }
        
        /* ================= NAVIGATION ================= */
        .navbar-premium {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-lg);
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        
        .navbar-brand img {
            height: 36px;
            width: auto;
            filter: brightness(0) invert(1);
            transition: transform 0.3s ease;
        }
        
        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            margin: 0 0.15rem;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
        }
        
        .nav-link i {
            margin-right: 6px;
            font-size: 0.9em;
        }
        
        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 0.75rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.25);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: 8px;
            margin-top: 8px;
            padding: 0.5rem 0;
            min-width: 220px;
            animation: fadeIn 0.2s ease-out;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.25rem;
            color: #374151;
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin: 0 0.5rem;
            width: auto;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #1f2937;
            transform: translateX(4px);
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            color: #6b7280;
        }
        
        .dropdown-divider {
            margin: 0.5rem 1rem;
            border-color: var(--border-color);
        }
        
        .badge-suspended {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            margin-left: 8px;
        }
        
        .user-greeting {
            color: rgba(255, 255, 255, 0.95);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none !important;
        }
        
        .user-greeting:hover {
            color: #ffffff;
        }
        
        .logout-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: rgba(255, 255, 255, 0.9);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-weight: 500;
            text-decoration: none !important;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
        }
        /* ================= MULTI-COLUMN DROPDOWN (ENHANCED) ================= */
.dropdown-menu.multi-column {
  width: 600px;
  padding: 1rem;
  border-radius: 12px;
  box-shadow: var(--shadow-lg);
  border: none;
  background: white;
  animation: fadeIn 0.25s ease-out;
  column-count: 3;
  column-gap: 1.5rem;
  /* Prevent text from breaking awkwardly */
  orphans: 3;
  widows: 3;
}

/* Ensure each item stays intact in one column */
.dropdown-menu.multi-column .dropdown-item {
  break-inside: avoid;
  display: flex;
  align-items: center;
  padding: 0.65rem 0.25rem;
  margin: 0.25rem 0;
  border-radius: 8px;
  color: #374151;
  font-weight: 500;
  font-size: 0.92rem;
  transition: all 0.2s ease;
  text-decoration: none;
  /* Full width within column */
  width: calc(100% - 0.5rem);
  /* Reset any inherited transforms */
  transform: none !important;
}

.dropdown-menu.multi-column .dropdown-item i {
  width: 24px;
  font-size: 1rem;
  color: var(--secondary-color);
  margin-right: 10px;
  display: inline-block;
  text-align: center;
}

/* Hover effect: subtle lift + background */
.dropdown-menu.multi-column .dropdown-item:hover {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  color: var(--primary-color);
  transform: translateX(4px);
  box-shadow: 0 2px 6px rgba(37, 99, 235, 0.12);
  z-index: 1; /* Ensures hover doesn’t get clipped */
  position: relative;
}

/* Divider: full-width, not split across columns */
.dropdown-menu.multi-column .dropdown-divider {
  margin: 0.8rem 0;
  border-color: var(--border-color);
  break-inside: avoid;
  /* Span full width by forcing single column at divider */
  column-span: all;
}

/* Reduce columns on smaller screens */
@media (max-width: 992px) {
  .dropdown-menu.multi-column {
    width: 380px;
    column-count: 2;
    column-gap: 1.25rem;
  }
}

@media (max-width: 768px) {
  .dropdown-menu.multi-column {
    width: 320px;
    column-count: 1;
    padding: 0.75rem;
  }

  .dropdown-menu.multi-column .dropdown-item {
    padding: 0.75rem 1rem;
    width: 100%;
  }
}
        /* ================= MAIN CONTENT ================= */
        main {
            min-height: calc(100vh - 200px);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }
        
        .page-header {
            padding: 2rem 0 1.5rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 2rem;
            background: white;
        }
        
        .page-title {
            font-weight: 700;
            color: #111827;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        /* ================= TOASTS & ALERTS ================= */
        .toast-container {
            z-index: 1060;
        }
        
        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid;
        }
        
        .alert-success {
            border-left-color: #10b981;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        
        .alert-danger {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        }
        
        .btn-close:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
        
        /* ================= FOOTER ================= */
        .footer-premium {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            color: #d1d5db;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 3rem;
            margin-bottom: 2rem;
        }
        
        .footer-logo {
            flex: 1;
            min-width: 250px;
        }
        
        .footer-logo img {
            height: 40px;
            margin-bottom: 1rem;
            filter: brightness(0) invert(1);
        }
        
        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .footer-tagline {
            color: #9ca3af;
            line-height: 1.6;
            max-width: 300px;
        }
        
        .footer-links {
            flex: 2;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
        }
        
        .footer-column h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        
        .footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-column ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-column ul li a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .footer-column ul li a:hover {
            color: white;
            transform: translateX(3px);
        }
        
        .footer-column ul li a i {
            font-size: 0.8rem;
        }
        
        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: #9ca3af;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            justify-content: center;
        }
        
        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: #d1d5db;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .social-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        /* ================= ANIMATIONS ================= */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .navbar-collapse {
                background: rgba(30, 64, 175, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 8px;
                padding: 1rem;
                margin-top: 0.5rem;
                box-shadow: var(--shadow-lg);
            }
            
            .nav-link {
                margin: 0.25rem 0;
            }
            
            .footer-content {
                flex-direction: column;
                gap: 2rem;
            }
            
            .footer-links {
                width: 100%;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand img {
                height: 32px;
            }
            
            .footer-links {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Premium Navigation -->
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="https://innoricsystems.in/uploads/ngo-logo.png" alt="Logo">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('jobs.*')) active @endif" 
                               href="{{ route('jobs.index') }}">
                                <i class="fas fa-briefcase"></i>Jobs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('offers.*')) active @endif" 
                               href="{{ route('offers.index') }}">
                                <i class="fas fa-tag"></i>Offers
                            </a>
                        </li>
                    @endauth
                    
                    @auth
                        <!-- Super Admin -->
                        @if(auth()->user()->isSuperAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="superadminDropdown" 
                                   data-bs-toggle="dropdown">
                                    <i class="fas fa-crown"></i>Super Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('superadmin.admins.index') }}">
                                            <i class="fas fa-users-cog"></i>Manage Admins
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <!-- Admin -->
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" 
                                   data-bs-toggle="dropdown">
                                    <i class="fas fa-cog"></i>Admin Panel
                                </a>
                                <ul class="dropdown-menu multi-column">
  <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
      <i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.approval-queue') }}">
      <i class="fas fa-hourglass-half"></i> Approval Queue</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.expired-content') }}">
      <i class="fas fa-clock"></i> Expired Content</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.users-management') }}">
      <i class="fas fa-user-friends"></i> Users Management</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.user-logs') }}">
      <i class="fas fa-history"></i> User Logs</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.reported-jobs') }}">
      <i class="fas fa-flag"></i> Reported Jobs</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.all-reported-jobs') }}">
      <i class="fas fa-flag-checkered"></i> All Reported</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.user-analytics') }}">
      <i class="fas fa-chart-line"></i> Analytics</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.news.create') }}">
      <i class="fas fa-plus-circle"></i> Create News</a></li>
  <li><a class="dropdown-item" href="{{ route('admin.news.index') }}">
      <i class="fas fa-newspaper"></i> All News</a></li>
  <li><hr class="dropdown-divider"></li>
  <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
      <i class="fas fa-user-cog"></i> My Profile</a></li>
</ul>
                                {{-- <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.approval-queue') }}">
                                        <i class="fas fa-hourglass-half"></i>Approval Queue</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.expired-content') }}">
                                        <i class="fas fa-clock"></i>Expired Content</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users-management') }}">
                                        <i class="fas fa-user-friends"></i>Users Management</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.user-logs') }}">
                                        <i class="fas fa-history"></i>User Logs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.reported-jobs') }}">
                                        <i class="fas fa-flag"></i>Reported Jobs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.all-reported-jobs') }}">
                                        <i class="fas fa-flag-checkered"></i>All Reported</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.user-analytics') }}">
                                        <i class="fas fa-chart-line"></i>Analytics</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.news.create') }}">
                                        <i class="fas fa-plus-circle"></i>Create News</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.news.index') }}">
                                        <i class="fas fa-newspaper"></i>All News</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
                                        <i class="fas fa-user-cog"></i>My Profile</a></li>
                                </ul> --}}
                            </li>

                        <!-- Regular User -->
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" 
                                   data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i>My Account
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.jobs.my-jobs') }}">
                                        <i class="fas fa-briefcase"></i>My Jobs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.offers.my-offers') }}">
                                        <i class="fas fa-tag"></i>My Offers</a></li>
                                    <li><a class="dropdown-item" href="{{ route('news.index') }}">
                                        <i class="fas fa-newspaper"></i>All News</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="fas fa-user-edit"></i>Profile Settings</a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i>Login
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="nav-link" href="{{ route('register') }}" 
                               style="background: rgba(255,255,255,0.15);">
                                <i class="fas fa-user-plus"></i>Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('user.profile') }}" class="user-greeting">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ auth()->user()->full_name }}</span>
                                @if(auth()->user()->isSuspended())
                                    <span class="badge-suspended">Suspended</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item ms-3">
                            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
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
            <!-- Page Header -->
            @if(isset($header))
                <div class="page-header">
                    <h1 class="page-title">{{ $header }}</h1>
                    @if(isset($subheader))
                        <p class="page-subtitle">{{ $subheader }}</p>
                    @endif
                </div>
            @endif
            
            <!-- Toast Container -->
            <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Content -->
            @yield('content')
        </div>
    </main>

    <!-- Premium Footer -->
    <footer class="footer-premium">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="https://innoricsystems.in/uploads/ngo-logo.png" alt="Logo">
                    <div class="footer-brand">Job & Promos Portal</div>
                    <p class="footer-tagline">
                        Find your dream job and discover amazing promotions in one unified platform.
                    </p>
                </div>
                
                <div class="footer-links">
                    <div class="footer-column">
                        <h6>Platform</h6>
                        <ul>
                            <li><a href="{{ route('jobs.index') }}"><i class="fas fa-chevron-right"></i>Browse Jobs</a></li>
                            <li><a href="{{ route('offers.index') }}"><i class="fas fa-chevron-right"></i>View Offers</a></li>
                            <li><a href="{{ route('news.index') }}"><i class="fas fa-chevron-right"></i>Latest News</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h6>Account</h6>
                        <ul>
                            @auth
                                <li><a href="{{ route('user.dashboard') }}"><i class="fas fa-chevron-right"></i>Dashboard</a></li>
                                <li><a href="{{ route('user.profile') }}"><i class="fas fa-chevron-right"></i>Profile</a></li>
                                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                    <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-chevron-right"></i>Admin Panel</a></li>
                                @endif
                            @else
                                <li><a href="{{ route('login') }}"><i class="fas fa-chevron-right"></i>Login</a></li>
                                <li><a href="{{ route('register') }}"><i class="fas fa-chevron-right"></i>Register</a></li>
                            @endauth
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h6>Legal</h6>
                        <ul>
                            <li><a href="#"><i class="fas fa-chevron-right"></i>Privacy Policy</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i>Terms of Service</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i>Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Job & Promos Portal. All rights reserved.</p>
                <div class="social-links">
                    <a href="#" class="social-link" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="social-link" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>