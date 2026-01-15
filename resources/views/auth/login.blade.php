<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Interacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #f5f9ff 0%, #fff5fa 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Color scheme */
        :root {
            --primary-blue: #00adccff;
            --primary-pink: #ff1493;
            --light-blue: #e6f2ff;
            --light-pink: #ffe6f0;
            --accent-blue: #00adccff;
            --accent-pink: #ff0080;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-section img {
            height: 80px;
            width: auto;
            margin-bottom: 1rem;
        }
        
        .logo-section h1 {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .logo-section p {
            color: #666;
            font-size: 0.95rem;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 102, 204, 0.12);
            padding: 2.5rem;
            border: none;
        }
        
        .login-card h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 173, 204, 0.15);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        }
        
        .form-check-input {
            border-radius: 6px;
            width: 1.2rem;
            height: 1.2rem;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .form-check-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 1rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 173, 204, 0.3);
            color: white;
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-info {
            background-color: rgba(0, 173, 204, 0.1);
            color: #0066cc;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .invalid-feedback {
            font-size: 0.85rem;
            display: block;
            margin-top: 0.25rem;
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }
        
        .divider span {
            background: white;
            padding: 0 10px;
            position: relative;
            color: #999;
            font-size: 0.85rem;
        }
        
        .links-section {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .links-section a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .links-section a:hover {
            color: var(--primary-pink);
            text-decoration: underline;
        }
        
        .links-section p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .register-prompt {
            background: linear-gradient(135deg, rgba(0, 173, 204, 0.05) 0%, rgba(255, 20, 147, 0.05) 100%);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .register-prompt p {
            margin-bottom: 0;
            color: #666;
        }
        
        .register-prompt a {
            font-weight: 700;
            color: var(--primary-blue);
            text-decoration: none;
        }
        
        .register-prompt a:hover {
            color: var(--primary-pink);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="{{ asset('images/knlogo.png') }}" alt="Interacts Logo">
            <h1>INTERACTS</h1>
            <p>Welcome back, learner! 👋</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <h2>Login to Your Account</h2>

            @if (session('info'))
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Oops! Something went wrong:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username or Email -->
                <div class="mb-3">
                    <label for="login" class="form-label">
                        <i class="bi bi-envelope me-2"></i>Username or Email
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('login') is-invalid @enderror" 
                        id="login" 
                        name="login" 
                        value="{{ old('login') }}" 
                        required 
                        autofocus
                        placeholder="Enter your username or email"
                    >
                    @error('login')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-2"></i>Password
                    </label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        required
                        placeholder="Enter your password"
                    >
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="remember" 
                        name="remember"
                    >
                    <label class="form-check-label" for="remember">
                        <i class="bi bi-bookmark me-1"></i>Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>

                <!-- Forgot Password Link -->
                <div class="links-section">
                    <a href="{{ route('password.request') }}">
                        <i class="bi bi-question-circle me-1"></i>Forgot your password?
                    </a>
                </div>

                <!-- Divider -->
                <div class="divider">
                    <span>or</span>
                </div>

                <!-- Register Prompt -->
                <div class="register-prompt">
                    <p>Don't have an account yet?</p>
                    <a href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-1"></i>Register as Student
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>