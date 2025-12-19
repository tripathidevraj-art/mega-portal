<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register/success';

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Show registration success page.
     *
     * @return \Illuminate\View\View
     */
    public function registrationSuccess()
    {
        return view('auth.registration-success');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // Send email verification
        $this->sendVerificationEmail($user);

        return redirect()->route('register.success')->with('success', 
            'Registration successful! Please check your email to verify your account.');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // Basic Info
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            
            // Address
            'country' => ['required', 'string', 'max:100'],
            'current_address' => ['required', 'string', 'max:500'],
            
            // Professional
            'occupation' => ['nullable', 'string', 'max:100'],
            'company' => ['nullable', 'string', 'max:200'],
            'skills' => ['nullable', 'string', 'max:1000'],
            
            // Documents
            'civil_id' => ['nullable', 'string', 'max:50', 'unique:users'],
            'passport_number' => ['nullable', 'string', 'max:50', 'unique:users'],
            'passport_expiry' => ['nullable', 'date', 'after:today'],
            'residency_type' => ['nullable', 'string', 'max:50'],
            'residency_expiry' => ['nullable', 'date', 'after:today'],
            
            // Volunteer
            'volunteer_interests' => ['nullable', 'string', 'max:1000'],
            
            // Account
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ], [
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'passport_expiry.after' => 'Passport expiry must be a future date.',
            'residency_expiry.after' => 'Residency expiry must be a future date.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Parse skills and volunteer interests from comma-separated to array
        $skillsArray = isset($data['skills']) ? array_map('trim', explode(',', $data['skills'])) : [];
        $volunteerArray = isset($data['volunteer_interests']) ? array_map('trim', explode(',', $data['volunteer_interests'])) : [];

        $user = User::create([
            // Basic Info
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            
            // Address
            'country' => $data['country'],
            'current_address' => $data['current_address'],
            
            // Professional
            'occupation' => $data['occupation'] ?? null,
            'company' => $data['company'] ?? null,
            'skills' => $skillsArray,
            
            // Documents
            'civil_id' => $data['civil_id'] ?? null,
            'passport_number' => $data['passport_number'] ?? null,
            'passport_expiry' => $data['passport_expiry'] ?? null,
            'residency_type' => $data['residency_type'] ?? null,
            'residency_expiry' => $data['residency_expiry'] ?? null,
            
            // Volunteer
            'volunteer_interests' => $volunteerArray,
            
            // Account
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'status' => 'pending',
            
            // Email Verification
            'verification_token' => Str::random(60),
        ]);

        // Log user registration activity
        UserActivityLog::create([
            'user_id' => $user->id,
            'action_type' => 'profile_updated',
            'reason' => 'User registered',
        ]);

        return $user;
    }

    /**
     * Send verification email.
     *
     * @param  User  $user
     * @return void
     */
    protected function sendVerificationEmail(User $user)
    {
        $verificationUrl = route('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email),
            'token' => $user->verification_token,
        ]);

        Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationUrl));
    }

    /**
     * Verify user email.
     *
     * @param  Request  $request
     * @param  int  $id
     * @param  string  $hash
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, $id, $hash, $token)
    {
        $user = User::findOrFail($id);

        if ($user->verification_token !== $token) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        if (sha1($user->email) !== $hash) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        $user->update([
            'email_verified_at' => now(),
            'status' => 'verified',
            'verification_token' => null,
        ]);

        // Log verification activity
        UserActivityLog::create([
            'user_id' => $user->id,
            'action_type' => 'profile_updated',
            'reason' => 'Email verified',
        ]);

        return redirect()->route('login')->with('success', 'Email verified successfully! You can now login.');
    }

    /**
     * Resend verification email.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return back()->with('error', 'Email is already verified.');
        }

        $user->update(['verification_token' => Str::random(60)]);
        $this->sendVerificationEmail($user);

        return back()->with('success', 'Verification email resent successfully.');
    }
}