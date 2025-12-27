<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// User Controllers
use App\Http\Controllers\User\JobController;
use App\Http\Controllers\User\JobApplicationController;
use App\Http\Controllers\User\OfferController;
use App\Http\Controllers\User\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\UserManagementController;

// Super Admin Controllers
use App\Http\Controllers\SuperAdmin\AdminManagementController;

// Middleware
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckSuperAdmin;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Jobs & Offers (Public)
|--------------------------------------------------------------------------
*/
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');

Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
Route::get('/offers/{id}', [OfferController::class, 'show'])->name('offers.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/register/success', [RegisterController::class, 'registrationSuccess'])->name('register.success');
/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/
Route::get('/email/verify/{id}/{hash}/{token}', [RegisterController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [RegisterController::class, 'resendVerification'])->name('verification.resend'); // ← ADD THIS
Route::get('/test-email', function () {
    $user = \App\Models\User::first();
    // Override email to your real Gmail
    $user->email = 'tripathidevraj2205@gmail.com'; // ← CHANGE THIS

    $url = url('/test');
    \Mail::to($user->email)->send(new \App\Mail\EmailVerificationMail($user, $url));
    return "Email sent to: " . $user->email;
});
Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {

        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('superadmin.admins.index');
        }

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('user.dashboard');

    })->name('user.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');

    // Job Applications
    Route::post('/jobs/{id}/apply', [JobApplicationController::class, 'apply'])->name('jobs.apply');
    Route::get('/my-applications', [JobApplicationController::class, 'myApplications'])
        ->name('user.jobs.applications');

    Route::get('/my-jobs/{id}/applications', [JobApplicationController::class, 'jobApplications'])
        ->name('user.jobs.job-applications');

    Route::post('/applications/{id}/update-status', [JobApplicationController::class, 'updateStatus'])
        ->name('applications.update-status');

    // User Jobs
    Route::prefix('user/jobs')->name('user.jobs.')->group(function () {
        Route::get('/my-jobs', [JobController::class, 'myJobs'])->name('my-jobs');
        Route::get('/create', [JobController::class, 'create'])->name('create');
        Route::post('/store', [JobController::class, 'store'])->name('store');
        Route::get('/{id}/share', [JobController::class, 'share'])->name('share');
    });

    // User Offers
    Route::prefix('user/offers')->name('user.offers.')->group(function () {
        Route::get('/my-offers', [OfferController::class, 'myOffers'])->name('my-offers');
        Route::get('/create', [OfferController::class, 'create'])->name('create');
        Route::post('/store', [OfferController::class, 'store'])->name('store');
        Route::get('/{id}/share', [OfferController::class, 'share'])->name('share');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        // Approval Queue
        Route::get('/approval-queue', [AdminController::class, 'approvalQueue'])->name('approval-queue');
        
        Route::post('/jobs/{id}/approve', [ApprovalController::class, 'approveJob'])->name('jobs.approve');
        Route::post('/jobs/{id}/reject', [ApprovalController::class, 'rejectJob'])->name('jobs.reject');
        Route::post('/offers/{id}/approve', [ApprovalController::class, 'approveOffer'])->name('offers.approve');
        Route::post('/offers/{id}/reject', [ApprovalController::class, 'rejectOffer'])->name('offers.reject');
        
        // Expired Content
        Route::get('/expired-content', [AdminController::class, 'expiredContent'])->name('expired-content');
        
        // User Management
        Route::get('/users', [AdminController::class, 'usersManagement'])->name('users-management');
        Route::get('/users/{id}/details', [UserManagementController::class, 'getUserDetails'])->name('user.details');
        Route::post('/users/{id}/suspend', [UserManagementController::class, 'suspendUser'])->name('user.suspend');
        Route::post('/users/{id}/activate', [UserManagementController::class, 'activateUser'])->name('user.activate');
        Route::post('/users/{id}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{id}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
        // Route::post('/users/{id}/approve', [AdminController::class, 'approveUser'])->name('user.approve');
        // Route::post('/users/{id}/reject', [AdminController::class, 'rejectUser'])->name('user.reject');

        // Logs
        Route::get('/user-logs', [AdminController::class, 'userLogs'])->name('user-logs');
        Route::get('/admin-logs', [AdminController::class, 'adminLogs'])->name('admin-logs');

        // Analytics
        Route::get('/user-analytics', [AdminController::class, 'userAnalytics'])->name('user-analytics');
    });

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminManagementController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
        Route::delete('/admins/{id}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');
    });

/*
|--------------------------------------------------------------------------
| Middleware Aliases
|--------------------------------------------------------------------------
*/
Route::aliasMiddleware('admin', CheckAdmin::class);
Route::aliasMiddleware('superadmin', CheckSuperAdmin::class);
