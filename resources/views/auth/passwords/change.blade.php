<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Interacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.change.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                                <input 
                                    type="password" 
                                    class="form-control @error('current_password') is-invalid @enderror" 
                                    id="current_password" 
                                    name="current_password" 
                                    required 
                                    autofocus
                                >
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                                <input 
                                    type="password" 
                                    class="form-control @error('new_password') is-invalid @enderror" 
                                    id="new_password" 
                                    name="new_password" 
                                    required
                                >
                                <small class="text-muted">Minimum 8 characters</small>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="new_password_confirmation" 
                                    name="new_password_confirmation" 
                                    required
                                >
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-key me-2"></i>Change Password
                                </button>
                                
                                @if(!auth()->user()->must_change_password)
                                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>