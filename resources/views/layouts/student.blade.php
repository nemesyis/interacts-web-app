<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student') - Interacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #6f42c1 0%, #5a32a3 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="text-white mb-4">
                        <i class="bi bi-mortarboard-fill me-2"></i>Interacts
                    </h4>
                    <div class="text-white-50 small mb-4">
                        Student Panel
                    </div>
                </div>

                <nav class="nav flex-column px-3">
                    <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('student.join*') ? 'active' : '' }}" href="{{ route('student.join') }}">
                        <i class="bi bi-plus-circle me-2"></i>Join Classroom
                    </a>
                    
                    <hr class="text-white-50">
                    
                    <div class="text-white-50 small px-3 mb-2">MY CLASSROOMS</div>
                    @if(isset($enrollments) && $enrollments->count() > 0)
                        @foreach($enrollments as $enrollment)
                            <a class="nav-link small" href="{{ route('student.classroom.view', $enrollment->classroom_id) }}">
                                <i class="bi bi-door-open me-2"></i>{{ Str::limit($enrollment->classroom->classroom_name, 20) }}
                            </a>
                        @endforeach
                    @else
                        <div class="text-white-50 small px-3">No classrooms joined</div>
                    @endif
                    
                    <hr class="text-white-50">

                    <a class="nav-link" href="{{ route('password.change') }}">
                        <i class="bi bi-key me-2"></i>Change Password
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </nav>

                <div class="p-3 mt-auto">
                    <div class="text-white-50 small">
                        Logged in as<br>
                        <strong class="text-white">{{ auth()->user()->full_name }}</strong>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-4 mb-0" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-4 mb-0" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show m-4 mb-0" role="alert">
                        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show m-4 mb-0" role="alert">
                        <h5 class="alert-heading">Please fix the following errors:</h5>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>