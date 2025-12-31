@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Teacher Dashboard</h1>
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
                            <p class="text-muted mb-1 small">Total Students</p>
                            <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-people text-success fs-4"></i>
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
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-calendar-check text-info fs-4"></i>
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
                            <p class="text-muted mb-1 small">Open Appointments</p>
                            <h3 class="mb-0">{{ $stats['open_appointments'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="bi bi-unlock text-warning fs-4"></i>
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
                </div>
                <div class="card-body">
                    @if($classrooms->count() > 0)
                        <div class="row g-3">
                            @foreach($classrooms as $classroom)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $classroom->classroom_name }}</h5>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($classroom->description, 80) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-people me-1"></i>{{ $classroom->enrollments_count }} students
                                                </span>
                                                <span class="badge bg-{{ $classroom->token_is_active ? 'success' : 'secondary' }}">
                                                    {{ $classroom->token_is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <a href="{{ route('teacher.appointments', $classroom->classroom_id) }}" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-calendar3 me-1"></i>View Appointments
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Classrooms Assigned</h5>
                            <p class="text-muted">You don't have any classrooms yet. Please contact the administrator.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    @if($upcomingAppointments->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Upcoming Appointments</h5>
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
                                                <span class="ms-2">
                                                    <i class="bi bi-clock me-1"></i>{{ $appointment->duration_minutes }} mins
                                                </span>
                                            </p>
                                        </div>
                                        <span class="badge bg-{{ $appointment->is_open ? 'success' : 'secondary' }}">
                                            {{ $appointment->is_open ? 'Open' : 'Closed' }}
                                        </span>
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