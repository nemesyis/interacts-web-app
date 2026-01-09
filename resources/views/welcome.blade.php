<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Interacts E-Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px;
            color: white;
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        .btn-custom {
            padding: 12px 40px;
            font-size: 18px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-login {
            background: white;
            color: #667eea;
            border: none;
        }
        .btn-login:hover {
            background: #667eea;
            color: #f8f9fa;
            border: 2px solid white;
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(255,255,255,0.3);
        }
        .btn-register {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        .btn-register:hover {
            background: white;
            color: #667eea;
        }
        .logo {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="logo mb-4">
                        <i class="bi bi-mortarboard-fill me-3"></i>Interacts
                    </div>
                    <h1 class="display-4 fw-bold mb-4">Modern E-Learning Platform</h1>
                    <p class="lead mb-4">
                        Empower education with our comprehensive learning management system. 
                        Create classrooms, manage appointments, track progress, and engage students effectively.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        @auth
                            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-login btn-custom">
                                <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login btn-custom">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-register btn-custom">
                                <i class="bi bi-person-plus me-2"></i>Register as Student
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-card text-center h-100">
                                <i class="bi bi-people-fill feature-icon"></i>
                                <h4>For Admins</h4>
                                <p class="mb-0">Manage teachers, create classrooms, and oversee the entire platform</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card text-center h-100">
                                <i class="bi bi-person-workspace feature-icon"></i>
                                <h4>For Teachers</h4>
                                <p class="mb-0">Create appointments, upload materials, build quizzes, and track student progress</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card text-center h-100">
                                <i class="bi bi-journal-bookmark-fill feature-icon"></i>
                                <h4>For Students</h4>
                                <p class="mb-0">Join classrooms, access materials, take quizzes, and submit projects</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card text-center h-100">
                                <i class="bi bi-graph-up-arrow feature-icon"></i>
                                <h4>Track Progress</h4>
                                <p class="mb-0">Comprehensive reports, auto-grading, and real-time analytics</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="row mt-5 pt-5">
                <div class="col-12 text-center mb-4">
                    <h2 class="display-6 fw-bold">Key Features</h2>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <i class="bi bi-calendar-check display-4 mb-3"></i>
                        <h5>Appointment Management</h5>
                        <p class="small">Schedule and organize learning sessions efficiently</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <i class="bi bi-clipboard-check display-4 mb-3"></i>
                        <h5>Auto-Graded Quizzes</h5>
                        <p class="small">Create quizzes with instant automatic grading</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <i class="bi bi-file-earmark-arrow-up display-4 mb-3"></i>
                        <h5>Project Submissions</h5>
                        <p class="small">Students submit work, teachers review and provide feedback</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <i class="bi bi-file-text display-4 mb-3"></i>
                        <h5>Comprehensive Reports</h5>
                        <p class="small">Detailed performance tracking and analytics</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="row mt-5 pt-5">
                <div class="col-12 text-center">
                    <p class="mb-0 opacity-75">
                        <small>&copy; {{ date('Y') }} Interacts E-Learning Platform. Built with Laravel & Bootstrap.</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>