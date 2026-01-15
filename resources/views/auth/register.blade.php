<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Interacts</title>
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
            padding: 20px 0;
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
        
        .register-container {
            width: 100%;
            max-width: 550px;
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
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 102, 204, 0.12);
            padding: 2.5rem;
            border: none;
        }
        
        .register-card h2 {
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
        
        .required-star {
            color: #ff6b6b;
            font-weight: 700;
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
        
        .form-text {
            font-size: 0.85rem;
            color: #999;
            margin-top: 0.35rem;
            display: block;
        }
        
        .invalid-feedback {
            font-size: 0.85rem;
            display: block;
            margin-top: 0.25rem;
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 1rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1.5rem;
        }
        
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 173, 204, 0.3);
            color: white;
        }
        
        .login-prompt {
            background: linear-gradient(135deg, rgba(0, 173, 204, 0.05) 0%, rgba(255, 20, 147, 0.05) 100%);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .login-prompt p {
            margin-bottom: 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .login-prompt a {
            font-weight: 700;
            color: var(--primary-blue);
            text-decoration: none;
        }
        
        .login-prompt a:hover {
            color: var(--primary-pink);
        }
        
        .form-row-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .form-row-group .mb-3 {
            margin-bottom: 0;
        }
        
        @media (max-width: 576px) {
            .form-row-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="{{ asset('images/knlogo.png') }}" alt="Interacts Logo">
            <h1>INTERACTS</h1>
            <p>Join our learning community! 🚀</p>
        </div>

        <!-- Register Card -->
        <div class="register-card">
            <h2>Create Your Student Account</h2>

            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px; border: none;">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Please fix these errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="full_name" class="form-label">
                        <i class="bi bi-person me-2"></i>Full Name <span class="required-star">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('full_name') is-invalid @enderror" 
                        id="full_name" 
                        name="full_name" 
                        value="{{ old('full_name') }}" 
                        required 
                        autofocus
                        placeholder="Enter your full name"
                    >
                    @error('full_name')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="bi bi-at me-2"></i>Username <span class="required-star">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('username') is-invalid @enderror" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}" 
                        required
                        placeholder="Choose a unique username"
                    >
                    <span class="form-text">
                        <i class="bi bi-info-circle me-1"></i>Letters, numbers, dashes and underscores only
                    </span>
                    @error('username')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-2"></i>Email Address <span class="required-star">*</span>
                    </label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        placeholder="Enter your email address"
                    >
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-2"></i>Password <span class="required-star">*</span>
                    </label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        required
                        placeholder="Create a strong password"
                    >
                    <span class="form-text">
                        <i class="bi bi-shield-check me-1"></i>Minimum 8 characters
                    </span>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        <i class="bi bi-lock-check me-2"></i>Confirm Password <span class="required-star">*</span>
                    </label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        placeholder="Re-enter your password"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-register">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                </button>

                <!-- Login Prompt -->
                <div class="login-prompt">
                    <p>Already have an account?</p>
                    <a href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login here
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>