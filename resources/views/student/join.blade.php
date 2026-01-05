@extends('layouts.student')

@section('title', 'Join Classroom')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Join a Classroom</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        Enter the access token provided by your teacher to join a classroom.
                    </p>

                    <form method="POST" action="{{ route('student.join.submit') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="access_token" class="form-label">Access Token <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg text-center @error('access_token') is-invalid @enderror" 
                                id="access_token" 
                                name="access_token" 
                                value="{{ old('access_token') }}" 
                                placeholder="Enter token (e.g. ABC123XY)"
                                style="letter-spacing: 2px; font-weight: bold;"
                                required
                                autofocus
                            >
                            @error('access_token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tokens are not case-sensitive</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Join Classroom
                            </button>
                            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card border-0 shadow-sm mt-3 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-question-circle text-primary me-2"></i>Need Help?
                    </h6>
                    <ul class="small mb-0">
                        <li>Ask your teacher for the classroom access token</li>
                        <li>Make sure the token is active</li>
                        <li>Tokens are usually 8 characters (letters and numbers)</li>
                        <li>You can join multiple classrooms</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection