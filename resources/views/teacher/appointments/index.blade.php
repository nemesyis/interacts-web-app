@extends('layouts.teacher')

@section('title', 'Appointments - ' . $classroom->classroom_name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
            <h1 class="h3 mb-0">{{ $classroom->classroom_name }}</h1>
            <p class="text-muted">Manage appointments and sessions</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('teacher.appointments.create', $classroom->classroom_id) }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-2"></i>Create Appointment
            </a>
        </div>
    </div>

    <!-- Classroom Info -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-2"><strong>Description:</strong></p>
                            <p class="text-muted">{{ $classroom->description ?? 'No description provided' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Access Token:</strong></p>
                            <code class="fs-5">{{ $classroom->access_token }}</code>
                            <span class="badge bg-{{ $classroom->token_is_active ? 'success' : 'secondary' }} ms-2">
                                {{ $classroom->token_is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Appointments</h5>
        </div>
        <div class="card-body">
            @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Appointment Title</th>
                                <th>Scheduled</th>
                                <th>Duration</th>
                                <th>Materials</th>
                                <th>Quiz</th>
                                <th>Report</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td><strong>{{ $appointment->appointment_number }}</strong></td>
                                    <td>
                                        <strong>{{ $appointment->appointment_title }}</strong>
                                        @if($appointment->description)
                                            <br><small class="text-muted">{{ Str::limit($appointment->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            <i class="bi bi-calendar me-1"></i>{{ $appointment->scheduled_date ? $appointment->scheduled_date->format('M d, Y') : 'Not set' }}
                                            @if($appointment->scheduled_time)
                                                <br><i class="bi bi-clock me-1"></i>{{ date('g:i A', strtotime($appointment->scheduled_time)) }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{ $appointment->duration_minutes }} mins</td>
                                    <td>
                                        <a href="{{ route('teacher.materials', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-file-earmark"></i> {{ $appointment->materials_count ?? 0 }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($appointment->quiz)
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Created</span>
                                        @else
                                            <a href="{{ route('teacher.quiz.create', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-plus"></i> Add
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appointment->report)
                                            <a href="{{ route('teacher.report.view', $appointment->appointment_id) }}" class="badge bg-success text-decoration-none">
                                                <i class="bi bi-file-text"></i> View
                                            </a>
                                        @else
                                            <a href="{{ route('teacher.report.view', $appointment->appointment_id) }}" class="badge bg-secondary text-decoration-none">
                                                <i class="bi bi-plus"></i> Create
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('teacher.appointments.toggle', $appointment->appointment_id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $appointment->is_open ? 'success' : 'secondary' }}">
                                                <i class="bi bi-{{ $appointment->is_open ? 'unlock' : 'lock' }}"></i>
                                                {{ $appointment->is_open ? 'Open' : 'Closed' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('teacher.materials', $appointment->appointment_id) }}">
                                                        <i class="bi bi-file-earmark me-2"></i>Manage Materials
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('teacher.report.view', $appointment->appointment_id) }}">
                                                        <i class="bi bi-file-text me-2"></i>View/Edit Report
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No Appointments Yet</h5>
                    <p class="text-muted mb-4">Create your first appointment to get started</p>
                    <a href="{{ route('teacher.appointments.create', $classroom->classroom_id) }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Create Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection