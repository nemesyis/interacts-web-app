@extends('layouts.student')

@section('title', $appointment->appointment_title)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('student.classroom.view', $appointment->classroom_id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Classroom
            </a>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3 mb-0">{{ $appointment->appointment_title }}</h1>
                    <p class="text-muted">
                        {{ $appointment->classroom->classroom_name }} • 
                        Appointment #{{ $appointment->appointment_number }}
                    </p>
                </div>
                <span class="badge bg-{{ $appointment->is_open ? 'success' : 'secondary' }} p-2">
                    <i class="bi bi-{{ $appointment->is_open ? 'unlock' : 'lock' }} me-1"></i>
                    {{ $appointment->is_open ? 'Open' : 'Closed' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Description -->
    @if($appointment->description)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Description</h6>
                        <p class="mb-0">{{ $appointment->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Materials Section -->
            @if($appointment->materials->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark me-2"></i>Materials
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($appointment->materials as $material)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-primary me-2">
                                                @switch($material->material_type)
                                                    @case('video')
                                                        <i class="bi bi-camera-video"></i> Video
                                                        @break
                                                    @case('pdf')
                                                        <i class="bi bi-file-pdf"></i> PDF
                                                        @break
                                                    @case('slides')
                                                        <i class="bi bi-file-slides"></i> Slides
                                                        @break
                                                    @case('document')
                                                        <i class="bi bi-file-text"></i> Document
                                                        @break
                                                    @case('link')
                                                        <i class="bi bi-link-45deg"></i> Link
                                                        @break
                                                @endswitch
                                            </span>
                                            <strong>{{ $material->material_title }}</strong>
                                            @if($material->description)
                                                <br><small class="text-muted">{{ $material->description }}</small>
                                            @endif
                                        </div>
                                        <a href="{{ $material->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-box-arrow-up-right"></i> Open
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quiz Section -->
            @if($appointment->quiz)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-check me-2"></i>Quiz
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6>{{ $appointment->quiz->quiz_title }}</h6>
                        @if($appointment->quiz->description)
                            <p class="text-muted small">{{ $appointment->quiz->description }}</p>
                        @endif

                        <div class="d-flex gap-3 mb-3">
                            @if($appointment->quiz->time_limit_minutes)
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock"></i> Time Limit: {{ $appointment->quiz->time_limit_minutes }} mins
                                </span>
                            @endif
                            @if($appointment->quiz->passing_score)
                                <span class="badge bg-info">
                                    <i class="bi bi-trophy"></i> Passing Score: {{ $appointment->quiz->passing_score }}
                                </span>
                            @endif
                            <span class="badge bg-secondary">
                                <i class="bi bi-question-circle"></i> {{ $appointment->quiz->questions->count() }} questions
                            </span>
                        </div>

                        @if($quizAttempt)
                            <!-- Already taken -->
                            <div class="alert alert-{{ $quizAttempt->passed ? 'success' : 'warning' }}">
                                <h6 class="alert-heading">
                                    <i class="bi bi-{{ $quizAttempt->passed ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                                    Quiz {{ $quizAttempt->passed ? 'Passed' : 'Completed' }}
                                </h6>
                                <p class="mb-0">
                                    Your Score: <strong>{{ number_format($quizAttempt->score, 1) }}/{{ number_format($quizAttempt->total_points, 1) }}</strong>
                                    ({{ $quizAttempt->passed ? 'Passed' : 'Did not pass' }})
                                </p>
                                <small class="text-muted">Submitted {{ $quizAttempt->submitted_at->diffForHumans() }}</small>
                            </div>
                        @else
                            @if($appointment->is_open)
                                <a href="{{ route('student.quiz.take', $appointment->quiz->quiz_id) }}" class="btn btn-primary">
                                    <i class="bi bi-play-circle me-2"></i>Start Quiz
                                </a>
                            @else
                                <div class="alert alert-secondary mb-0">
                                    <i class="bi bi-lock me-2"></i>Quiz will be available when the appointment is open.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            <!-- Projects Section -->
            @if($projects->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-briefcase me-2"></i>Project Assignments
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($projects as $project)
                            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <h6>{{ $project->project_title }}</h6>
                                @if($project->description)
                                    <p class="text-muted small">{{ $project->description }}</p>
                                @endif

                                @if($project->due_date)
                                    <p class="small mb-2">
                                        <i class="bi bi-calendar me-1"></i>
                                        <strong>Due:</strong> {{ $project->due_date->format('M d, Y g:i A') }}
                                    </p>
                                @endif

                                @php
                                    $submission = $mySubmissions[$project->project_id] ?? null;
                                @endphp

                                @if($submission)
                                    <!-- Already submitted -->
                                    <div class="alert alert-success">
                                        <h6 class="alert-heading">
                                            <i class="bi bi-check-circle me-2"></i>Submitted
                                        </h6>
                                        <p class="mb-2">
                                            <strong>File:</strong> {{ $submission->file_name }}<br>
                                            <strong>Submitted:</strong> {{ $submission->submitted_at->format('M d, Y g:i A') }}
                                        </p>
                                        @if($submission->submission_note)
                                            <p class="mb-2"><strong>Note:</strong> {{ $submission->submission_note }}</p>
                                        @endif
                                        <a href="{{ $submission->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i> Download Your Submission
                                        </a>
                                        @if($appointment->is_open)
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="collapse" data-bs-target="#resubmit-{{ $project->project_id }}">
                                                <i class="bi bi-arrow-clockwise"></i> Resubmit
                                            </button>
                                        @endif
                                    </div>

                                    @if($appointment->is_open)
                                        <!-- Resubmit Form (hidden by default) -->
                                        <div class="collapse mt-2" id="resubmit-{{ $project->project_id }}">
                                            <form method="POST" action="{{ route('student.project.submit', $project->project_id) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-2">
                                                    <label class="form-label small">New File</label>
                                                    <input type="file" class="form-control" name="file" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small">Note (Optional)</label>
                                                    <textarea class="form-control" name="submission_note" rows="2"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">Upload New File</button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <!-- Not submitted -->
                                    @if($appointment->is_open)
                                        <form method="POST" action="{{ route('student.project.submit', $project->project_id) }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Upload Your Project File</label>
                                                <input type="file" class="form-control" name="file" required>
                                                <small class="text-muted">Any file type accepted</small>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Note (Optional)</label>
                                                <textarea class="form-control" name="submission_note" rows="2" placeholder="Add any notes about your submission..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-upload me-2"></i>Submit Project
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-secondary mb-0">
                                            <i class="bi bi-lock me-2"></i>Project submission will be available when the appointment is open.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Schedule Info -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Schedule</h6>
                    <p class="mb-1"><strong>Date:</strong> {{ $appointment->scheduled_date ? $appointment->scheduled_date->format('M d, Y') : 'Not set' }}</p>
                    <p class="mb-1"><strong>Time:</strong> {{ $appointment->scheduled_time ? date('g:i A', strtotime($appointment->scheduled_time)) : 'Not set' }}</p>
                    <p class="mb-0"><strong>Duration:</strong> {{ $appointment->duration_minutes }} minutes</p>
                </div>
            </div>

            <!-- Report Link -->
            @if($appointment->report)
                <div class="card border-0 shadow-sm mb-3 bg-info bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-file-text text-info me-2"></i>Report Available
                        </h6>
                        <p class="small mb-2">The teacher has published a report for this appointment.</p>
                        <a href="{{ route('student.report.view', $appointment->appointment_id) }}" class="btn btn-sm btn-info w-100">
                            <i class="bi bi-eye me-1"></i>View Report
                        </a>
                    </div>
                </div>
            @endif

            <!-- Teacher Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Teacher</h6>
                    <p class="mb-1"><strong>{{ $appointment->classroom->teacher->full_name }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection