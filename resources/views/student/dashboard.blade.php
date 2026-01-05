@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Student Dashboard</h1>
            <p class="text-muted">Welcome back, {{ auth()->user()->full_name }}!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">My Classrooms</p>
                            <h3 class="mb-0">{{ $stats['total_classrooms'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-door-open text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Appointments</p>
                            <h3 class="mb-0">{{ $stats['total_appointments'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-calendar-check text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Completed Quizzes</p>
                            <h3 class="mb-0">{{ $stats['completed_quizzes'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-check-circle text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Submitted Projects</p>
                            <h3 class="mb-0">{{ $stats['submitted_projects'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="bi bi-file-earmark text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Classrooms -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Classrooms</h5>
                    <a href="{{ route('student.join') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Join New Classroom
                    </a>
                </div>
                <div class="card-body">
                    @if($enrollments->count() > 0)
                        <div class="row g-3">
                            @foreach($enrollments as $enrollment)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $enrollment->classroom->classroom_name }}</h5>
                                            <p class="card-text text-muted small mb-3">
                                                <i class="bi bi-person-badge me-1"></i>
                                                {{ $enrollment->classroom->teacher->full_name }}
                                            </p>
                                            <div class="mb-3">
                                                <span class="badge bg-info">
                                                    {{ $enrollment->classroom->appointments->count() }} appointments
                                                </span>
                                                <span class="badge bg-secondary">
                                                    Joined {{ $enrollment->enrolled_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <a href="{{ route('student.classroom.view', $enrollment->classroom_id) }}" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-arrow-right-circle me-1"></i>View Classroom
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Classrooms Joined Yet</h5>
                            <p class="text-muted mb-4">Join your first classroom using an access token</p>
                            <a href="{{ route('student.join') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Join Classroom
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Open Appointments -->
    @if($upcomingAppointments->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Open Appointments</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($upcomingAppointments as $appointment)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $appointment->appointment_title }}</h6>
                                            <p class="mb-1 text-muted small">
                                                <i class="bi bi-door-open me-1"></i>{{ $appointment->classroom->classroom_name }}
                                            </p>
                                            <p class="mb-0 text-muted small">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $appointment->scheduled_date->format('M d, Y') }} at {{ date('g:i A', strtotime($appointment->scheduled_time)) }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="badge bg-success">Open</span>
                                            <a href="{{ route('student.appointment.view', $appointment->appointment_id) }}" class="btn btn-sm btn-primary ms-2">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection