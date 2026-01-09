<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'showChangePasswordForm', 'changePassword']);
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Get the login username to be used by the controller.
     * Allow login with either username or email
     */
    public function username()
    {
        return 'login'; // Custom field name
    }

    /**
     * Validate the user login request.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     */
        protected function credentials(Request $request)
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        return [
            $field => $login,
            'password_hash' => $request->input('password'),  // Change this line
        ];
    }

        protected function attemptLogin(Request $request)
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $user = User::where($field, $login)->first();
        
        if ($user && Hash::check($request->input('password'), $user->password_hash)) {
            Auth::login($user, $request->filled('remember'));
            return true;
        }
        
        return false;
    }

    /**
     * The user has been authenticated.
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if account is active
        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['login' => 'Your account is not active. Please contact administrator.']);
        }

        // Update last login
        $user->update(['last_login' => now()]);

        // Check if must change password
        if ($user->must_change_password) {
            return redirect()->route('password.change')
                ->with('info', 'You must change your password before continuing.');
        }

        // Redirect based on role
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show password change form
     */
    public function showChangePasswordForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * Handle password change
     */
    public function changePassword(Request $request)
    {
        // Validate input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'The password confirmation does not match.',
        ]);

        $user = auth()->user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password_hash)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        // Update password - IMPORTANT: Use direct assignment and save()
        $user->password_hash = Hash::make($request->new_password);
        $user->must_change_password = false;
        $user->save();

        // Force logout after password change
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Password changed successfully! Please login with your new password.');
    }

    /**
     * Handle a forgot-password request form submission.
     * Sends a notification email to the administrator address defined in .env.
     */
    public function sendPasswordRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $requesterEmail = $request->input('email');

        // Use configured mail `from` address as the admin recipient
        $adminEmail = config('mail.from.address');

        // Attempt to find the user by email
        $user = User::where('email', $requesterEmail)->first();

        if ($user) {
            // Generate a temporary password and set must_change_password flag
            $tempPassword = bin2hex(random_bytes(6)); // 12 hex chars
            $user->password_hash = Hash::make($tempPassword);
            $user->must_change_password = true;
            $user->save();

            // Email the temporary password to the user
            $userSubject = 'Your temporary password';
            $userBody = "Hello {$user->full_name},\n\n" .
                        "A password reset was requested for your account.\n" .
                        "Your temporary password is: {$tempPassword}\n\n" .
                        "Please log in and change your password immediately.\n\n" .
                        "If you did not request this, please contact the administrator.";

            try {
                Mail::raw($userBody, function ($message) use ($requesterEmail, $userSubject, $adminEmail) {
                    $message->to($requesterEmail)
                            ->subject($userSubject)
                            ->bcc($adminEmail);
                });
            } catch (\Exception $e) {
                logger()->error('Failed to send temporary password email: ' . $e->getMessage());
                return redirect()->back()->withErrors(['email' => 'Failed to send email. Please try again later.']);
            }

            return redirect()->back()->with('status', 'A temporary password has been emailed to you.');
        }

        // If user not found, still notify admin about the request (avoid account enumeration)
        $subject = "Password reset request: {$requesterEmail}";
        $body = "A password reset was requested for: {$requesterEmail}\nIP: {$request->ip()}\nTime: " . now();

        try {
            Mail::raw($body, function ($message) use ($adminEmail, $subject) {
                $message->to($adminEmail)
                        ->subject($subject);
            });
        } catch (\Exception $e) {
            logger()->error('Failed to notify admin about password request: ' . $e->getMessage());
        }

        return redirect()->back()->with('status', 'If an account exists for that email, instructions have been sent.');
    }
}