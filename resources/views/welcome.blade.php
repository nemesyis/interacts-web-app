<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Interacts E-Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
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
        
        /* Navigation */
        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 102, 204, 0.08);
            padding: 1rem 0;
        }
        
        .navbar .container {
        display: flex;
        justify-content: center;
        align-items: center;
        }   

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand img {
            height: 70px;
            width: auto;
        }
        
        .navbar-brand span {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Hero Section */
        .hero-section {
            min-height: 90vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f5f9ff 0%, #fff5fa 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 20, 147, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(0, 102, 204, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-section p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.8;
        }
        
        .btn-primary-custom {
            background: var(--primary-blue);
            color: white;
            border: 2px solid var(--primary-blue);
            padding: 12px 38px;
            font-size: 1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background: white;
            color: var(--primary-blue);
            transform: translateY(-3px);
            border: 2px solid var(--primary-blue);
        }
        
        .btn-secondary-custom {
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            padding: 12px 38px;
            font-size: 1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary-custom:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Feature Cards */
        .feature-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 102, 204, 0.12);
            border-color: var(--primary-blue);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-card h4 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .feature-card p {
            color: #666;
            font-size: 0.95rem;
        }
        
        /* Key Features Section */
        .features-section {
            padding: 80px 0;
            background: white;
        }
        
        .features-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 3rem;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-item {
            text-align: center;
            padding: 20px;
        }
        
        .feature-item i {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-item h5 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .feature-item p {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        
        .footer p {
            margin-bottom: 0;
            opacity: 0.9;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section {
                min-height: 70vh;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/knlogo.png') }}" alt="Interacts Logo">
                <span>INTERACTS</span>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content mb-5 mb-lg-0">
                    <h1>Integrated Assessment and Tracking System</h1>
                    <p>
                        Empower education with Interacts - our comprehensive learning management system. 
                        Create classrooms, manage appointments, track progress, and engage students effectively.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        @auth
                            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-primary-custom">
                                <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary-custom">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-secondary-custom">
                                <i class="bi bi-person-plus me-2"></i>Register as Student
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="bi bi-people-fill feature-icon"></i>
                                <h4>For Admins</h4>
                                <p class="mb-0">Manage teachers, create classrooms, and oversee the entire platform</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="bi bi-person-workspace feature-icon"></i>
                                <h4>For Teachers</h4>
                                <p class="mb-0">Create appointments, upload materials, build quizzes, and track students</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="bi bi-journal-bookmark-fill feature-icon"></i>
                                <h4>For Students</h4>
                                <p class="mb-0">Join classrooms, access materials, take quizzes, and submit projects</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="bi bi-graph-up-arrow feature-icon"></i>
                                <h4>Track Progress</h4>
                                <p class="mb-0">Comprehensive reports, auto-grading, and real-time analytics</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section class="features-section">
        <div class="container">
            <h2>Why Choose Interacts?</h2>
            <div class="row">
                <div class="col-md-3 col-sm-6 feature-item">
                    <i class="bi bi-calendar-check"></i>
                    <h5>Smart Scheduling</h5>
                    <p>Schedule and organize learning sessions efficiently with automatic reminders</p>
                </div>
                <div class="col-md-3 col-sm-6 feature-item">
                    <i class="bi bi-clipboard-check"></i>
                    <h5>Auto-Graded Quizzes</h5>
                    <p>Create quizzes with instant automatic grading and detailed analytics</p>
                </div>
                <div class="col-md-3 col-sm-6 feature-item">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    <h5>Project Management</h5>
                    <p>Students submit work, teachers review and provide detailed feedback</p>
                </div>
                <div class="col-md-3 col-sm-6 feature-item">
                    <i class="bi bi-file-text"></i>
                    <h5>Analytics & Reports</h5>
                    <p>Detailed performance tracking and comprehensive analytics for all users</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Interacts E-Learning Platform. Built with <span style="color: var(--primary-pink);">❤</span> from Raka for KNS.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>