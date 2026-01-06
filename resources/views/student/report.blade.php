@extends('layouts.student')

@section('title', 'Report - ' . $appointment->appointment_title)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('student.appointment.view', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Appointment
            </a>
            <h1 class="h3 mb-0">{{ $appointment->report->report_title }}</h1>
            <p class="text-muted">
                {{ $appointment->classroom->classroom_name }} • 
                Appointment #{{ $appointment->appointment_number }}
            </p>
        </div>
    </div>

    <!-- Teacher's Explanation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text me-2"></i>Teacher's Explanation
                    </h5>
                </div>
                <div class="card-body">
                    <div style="white-space: pre-wrap;">{{ $appointment->report->report_content }}</div>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-person me-1"></i>{{ $appointment->classroom->teacher->full_name }} • 
                        <i class="bi bi-calendar ms-2 me-1"></i>{{ $appointment->report->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Results -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Class Results
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        You can see how other students performed in this appointment.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Quiz Score</th>
                                    <th>Project Submitted</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    @php
                                        $quizAttempt = $quizAttempts[$student->user_id] ?? null;
                                        $projects = $projectSubmissions[$student->user_id] ?? collect();
                                        $attended = $attendance[$student->user_id] ?? null;
                                        $isMe = $student->user_id == auth()->id();
                                    @endphp
                                    <tr class="{{ $isMe ? 'table-primary' : '' }}">
                                        <td>
                                            <strong>{{ $student->full_name }}</strong>
                                            @if($isMe)
                                                <span class="badge bg-primary ms-2">You</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($quizAttempt)
                                                <span class="badge bg-{{ $quizAttempt->passed ? 'success' : 'warning' }}">
                                                    {{ number_format($quizAttempt->score, 1) }}/{{ number_format($quizAttempt->total_points, 1) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not taken</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($projects->count() > 0)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Yes
                                                </span>
                                                @if($isMe)
                                                    <div class="mt-1">
                                                        @foreach($projects as $project)
                                                            <a href="{{ $project->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                                <i class="bi bi-download"></i> {{ Str::limit($project->file_name, 20) }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attended)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Present
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Absent</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection