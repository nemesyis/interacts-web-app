@extends('layouts.student')

@section('title', $classroom->classroom_name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
            <h1 class="h3 mb-0">{{ $classroom->classroom_name }}</h1>
            <p class="text-muted">
                <i class="bi bi-person-badge me-2"></i>{{ $classroom->teacher->full_name }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-success p-2">
                <i class="bi bi-check-circle me-1"></i>Enrolled
            </span>
        </div>
    </div>

    <!-- Classroom Info -->
    @if($classroom->description)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">About this Classroom</h6>
                        <p class="mb-0">{{ $classroom->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Appointments List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Appointments</h5>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($appointments as $appointment)
                                <div class="list-group-item px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-primary me-2">
                                                    #{{ $appointment->appointment_number }}
                                                </span>
                                                <h6 class="mb-0">{{ $appointment->appointment_title }}</h6>
                                                <span class="badge bg-{{ $appointment->is_open ? 'success' : 'secondary' }} ms-2">
                                                    {{ $appointment->is_open ? 'Open' : 'Closed' }}
                                                </span>
                                            </div>

                                            @if($appointment->description)
                                                <p class="text-muted small mb-2">{{ Str::limit($appointment->description, 100) }}</p>
                                            @endif

                                            <div class="d-flex gap-3 small text-muted">
                                                @if($appointment->scheduled_date)
                                                    <span>
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $appointment->scheduled_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                                @if($appointment->scheduled_time)
                                                    <span>
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ date('g:i A', strtotime($appointment->scheduled_time)) }}
                                                    </span>
                                                @endif
                                                <span>
                                                    <i class="bi bi-hourglass me-1"></i>
                                                    {{ $appointment->duration_minutes }} mins
                                                </span>
                                            </div>

                                            @if($appointment->is_open)
                                                <div class="mt-2">
                                                    <span class="badge bg-light text-dark me-1">
                                                        <i class="bi bi-file-earmark"></i> Materials Available
                                                    </span>
                                                    @if($appointment->quiz)
                                                        <span class="badge bg-light text-dark me-1">
                                                            <i class="bi bi-clipboard-check"></i> Quiz Available
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ms-3">
                                            <a href="{{ route('student.appointment.view', $appointment->appointment_id) }}" 
                                               class="btn btn-{{ $appointment->is_open ? 'primary' : 'outline-secondary' }}">
                                                <i class="bi bi-arrow-right-circle me-1"></i>
                                                {{ $appointment->is_open ? 'Enter' : 'View' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Appointments Yet</h5>
                            <p class="text-muted">Your teacher hasn't created any appointments yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection