<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
}