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
        \Log::info('REGISTER REQUEST RECEIVED', $request->all());
    $this->validator($request->all())->validate();

    event(new Registered($user = $this->create($request->all())));

    // 🔍 DEBUG: Log user & email
    \Log::info('📧 PREPARING TO SEND EMAIL', [
        'user_id' => $user->id,
        'email' => $user->email,
        'token' => $user->verification_token,
    ]);

    try {
        $this->sendVerificationEmail($user);
        \Log::info('✅ EMAIL SEND CALLED SUCCESSFULLY', ['email' => $user->email]);
    } catch (\Exception $e) {
        \Log::error('❌ EMAIL FAILED', [
            'email' => $user->email,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->withErrors(['email' => 'Failed to send verification email: ' . $e->getMessage()]);
    }

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
        'phone_country_code' => ['required', 'string', 'in:+91,+1,+44,+971,+966'],
        'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9]{6,15}$/'],
        'whatsapp_country_code' => ['nullable', 'string', 'in:+91,+1,+44,+971,+966'],
        'whatsapp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]{6,15}$/'],
        'date_of_birth' => ['required', 'date', 'before:-18 years', 'after:-80 years'],
        'gender' => ['required', 'in:male,female,other'],
        
        // Address
        'country' => ['required', 'string', 'max:100'],
        'state' => ['required', 'string', 'max:100'],
        'city' => ['required', 'string', 'max:100'],
        'zip_code' => ['required', 'string', 'max:20'],
        'current_address' => ['required', 'string', 'max:500'],
        'communication_address' => ['nullable', 'string', 'max:500'],
        
        // Professional
        'designation' => ['nullable', 'string', 'max:100'],
        'company_name' => ['nullable', 'string', 'max:200'],
        'industry_experience' => ['nullable', 'in:0-3,4-6,7-10,10+'],
        
        // Documents
        'civil_id' => ['nullable', 'string', 'max:50', 'unique:users'],
        'civil_id_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        'passport_number' => ['nullable', 'string', 'max:50', 'unique:users'],
        'passport_expiry' => ['nullable', 'date', 'after:today', 'before:+15 years'],
        'residency_type' => ['nullable', 'string', 'max:50'],
        'residency_expiry' => ['nullable', 'date', 'after:today', 'before:+5 years'],
        
        // Volunteer
        'volunteer_interests' => ['nullable', 'string', 'max:1000'],
        
        // New Section
        'additional_info' => ['nullable', 'string', 'max:2000'],
        
        // Account
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'terms' => ['required', 'accepted'],
    ], [
        'date_of_birth.before' => 'You must be at least 18 years old.',
        'date_of_birth.after' => 'Age must be under 80 years.',
        'passport_expiry.before' => 'Passport expiry cannot exceed 15 years.',
        'residency_expiry.before' => 'Residency expiry cannot exceed 5 years.',
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
    // Handle file upload
    $civilIdPath = null;
    if (!empty($data['civil_id_file'])) {
        $civilIdPath = $data['civil_id_file']->store('civil_ids', 'public');
    }

    // Parse volunteer interests
    $volunteerArray = isset($data['volunteer_interests']) 
        ? array_map('trim', explode(',', $data['volunteer_interests'])) 
        : [];

    $user = User::create([
        // Basic Info
        'full_name' => $data['full_name'],
        'email' => $data['email'],
        'phone_country_code' => $data['phone_country_code'],
        'phone' => $data['phone'],
        'whatsapp_country_code' => $data['whatsapp_country_code'] ?? null,
        'whatsapp' => $data['whatsapp'] ?? null,
        'date_of_birth' => $data['date_of_birth'],
        'gender' => $data['gender'],
        
        // Address
        'country' => $data['country'],
        'state' => $data['state'],
        'city' => $data['city'],
        'zip_code' => $data['zip_code'],
        'current_address' => $data['current_address'],
        'communication_address' => $data['communication_address'] ?? $data['current_address'],
        
        // Professional
        'designation' => $data['designation'] ?? null,
        'company_name' => $data['company_name'] ?? null,
        'industry_experience' => $data['industry_experience'] ?? null,
        
        // Documents
        'civil_id' => $data['civil_id'] ?? null,
        'civil_id_file_path' => $civilIdPath,
        'passport_number' => $data['passport_number'] ?? null,
        'passport_expiry' => $data['passport_expiry'] ?? null,
        'residency_type' => $data['residency_type'] ?? null,
        'residency_expiry' => $data['residency_expiry'] ?? null,
        
        // Volunteer
        'volunteer_interests' => json_encode($volunteerArray),
        
        // New Section
        'additional_info' => $data['additional_info'] ?? null,
        
        // Account
        'password' => Hash::make($data['password']),
        'role' => 'user',
        'status' => 'pending',
        'verification_token' => Str::random(60),
    ]);

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

    \Log::info('🔗 Generated verification URL', ['url' => $verificationUrl]);

    try {
        Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationUrl));
        \Log::info('📨 Mail::send() executed without exception');
    } catch (\Exception $e) {
        \Log::error('💥 MAIL SEND EXCEPTION', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ]);
        throw $e;
    }
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