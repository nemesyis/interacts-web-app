<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation - Interacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Accept Teacher Invitation</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            You've been invited to join <strong>Interacts</strong> as a teacher!
                        </div>

                        <div class="mb-4">
                            <p><strong>Full Name:</strong> {{ $invitation->teacher_full_name }}</p>
                            <p><strong>Email:</strong> {{ $invitation->teacher_email }}</p>
                            <p><strong>Username:</strong> <code>{{ $invitation->teacher_username }}</code></p>
                            <p><strong>Invited by:</strong> {{ $invitation->admin->full_name }}</p>
                        </div>

                        <form method="POST" action="{{ route('teacher.accept.submit', $invitation->invitation_token) }}">
                            @csrf

                            <h5 class="mb-3">Set Your Password</h5>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    autofocus
                                >
                                <small class="text-muted">Minimum 8 characters</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required
                                >
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Accept Invitation & Create Account
                                </button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                By accepting, you agree to the terms of service.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>