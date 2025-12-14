<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Interacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0d6efd 0%, #0a58ca 100%);
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
                        Admin Panel
                    </div>
                </div>

                <nav class="nav flex-column px-3">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.invite.*') ? 'active' : '' }}" href="{{ route('admin.invite.teacher') }}">
                        <i class="bi bi-person-plus me-2"></i>Invite Teacher
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.classrooms*') ? 'active' : '' }}" href="{{ route('admin.classrooms') }}">
                        <i class="bi bi-door-open me-2"></i>Classrooms
                    </a>
                    
                    <hr class="text-white-50">
                    
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

                @if(session('invitation_details'))
                    <div class="alert alert-info alert-dismissible fade show m-4 mb-0" role="alert">
                        <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Teacher Credentials</h5>
                        <p class="mb-2">Please provide these credentials to the teacher:</p>
                        <ul class="mb-0">
                            <li><strong>Email:</strong> {{ session('invitation_details')['email'] }}</li>
                            <li><strong>Username:</strong> {{ session('invitation_details')['username'] }}</li>
                            <li><strong>Temporary Password:</strong> <code>{{ session('invitation_details')['password'] }}</code></li>
                        </ul>
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