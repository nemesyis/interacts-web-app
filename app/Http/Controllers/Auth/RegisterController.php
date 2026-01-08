<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\TeacherInvitation;
use Carbon\Carbon;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/student/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show registration form (students only)
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'full_name' => ['required', 'string', 'max:200'],
            'username' => ['required', 'string', 'max:100', 'unique:tb_user,username', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tb_user,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.alpha_dash' => 'Username can only contain letters, numbers, dashes and underscores.',
            'username.unique' => 'This username is already taken.',
            'email.unique' => 'This email is already registered.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'full_name' => $data['full_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'role' => 'student', // Always student for self-registration
            'account_status' => 'active',
            'must_change_password' => false,
        ]);
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->guard()->login($user);

        return redirect($this->redirectTo)->with('success', 'Registration successful! Welcome to Interacts.');
    }

    /**
     * Show teacher invitation acceptance page
     */
    public function showAcceptInvitation($token)
    {
        $invitation = TeacherInvitation::where('invitation_token', $token)
            ->where('status', '!=', 'accepted')
            ->firstOrFail();

        // Check if expired
        if ($invitation->expires_at->isPast()) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('login')->with('error', 'This invitation has expired. Please contact the administrator.');
        }

        return view('auth.teacher-accept', compact('invitation'));
    }

    /**
     * Accept teacher invitation and create account
     */
    public function acceptInvitation(Request $request, $token)
    {
        $invitation = TeacherInvitation::where('invitation_token', $token)
            ->where('status', '!=', 'accepted')
            ->firstOrFail();

        // Check if expired
        if ($invitation->expires_at->isPast()) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('login')->with('error', 'This invitation has expired.');
        }

        // Validate password
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if user already exists with this email/username
        $existingUser = User::where('email', $invitation->teacher_email)
            ->orWhere('username', $invitation->teacher_username)
            ->first();

        if ($existingUser) {
            return back()->withErrors(['email' => 'A user with this email or username already exists.']);
        }

        // Create teacher account
        $teacher = User::create([
            'email' => $invitation->teacher_email,
            'username' => $invitation->teacher_username,
            'password_hash' => Hash::make($request->password),
            'full_name' => $invitation->teacher_full_name,
            'role' => 'teacher',
            'account_status' => 'active',
            'must_change_password' => false,
        ]);

        // Update invitation status
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Auto-login the teacher
        $this->guard()->login($teacher);

        return redirect()->route('teacher.dashboard')
            ->with('success', 'Welcome! Your account has been created successfully.');
    }
}