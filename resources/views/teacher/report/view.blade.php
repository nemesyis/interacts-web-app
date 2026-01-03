@extends('layouts.teacher')

@section('title', 'Report - ' . $appointment->appointment_title)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('teacher.appointments', $appointment->classroom_id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Appointments
            </a>
            <h1 class="h3 mb-0">{{ $appointment->appointment_title }} - Report</h1>
            <p class="text-muted">
                {{ $appointment->classroom->classroom_name }} • 
                Appointment #{{ $appointment->appointment_number }}
            </p>
        </div>
    </div>

    <!-- Teacher's Explanation Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Teacher's Explanation</h5>
                </div>
                <div class="card-body">
                    @if($appointment->report)
                        <!-- View Mode -->
                        <div id="report-view">
                            <div class="mb-3">
                                <strong>Title:</strong> {{ $appointment->report->report_title }}
                            </div>
                            <div class="mb-3">
                                <strong>Content:</strong>
                                <div class="mt-2 p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $appointment->report->report_content }}</div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="toggleEditMode()">
                                <i class="bi bi-pencil me-2"></i>Edit Report
                            </button>
                        </div>

                        <!-- Edit Mode -->
                        <div id="report-edit" style="display: none;">
                            <form method="POST" action="{{ route('teacher.report.update', $appointment->appointment_id) }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="report_title" class="form-label">Report Title <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="report_title" 
                                        name="report_title" 
                                        value="{{ $appointment->report->report_title }}" 
                                        required
                                    >
                                </div>

                                <div class="mb-3">
                                    <label for="report_content" class="form-label">Report Content <span class="text-danger">*</span></label>
                                    <textarea 
                                        class="form-control" 
                                        id="report_content" 
                                        name="report_content" 
                                        rows="15" 
                                        required
                                    >{{ $appointment->report->report_content }}</textarea>
                                    <small class="text-muted">Write a detailed explanation of the appointment, what was covered, and overall assessment.</small>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-2"></i>Save Changes
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="toggleEditMode()">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Create Form -->
                        <form method="POST" action="{{ route('teacher.report.create', $appointment->appointment_id) }}">
                            @csrf
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Create a report to document this appointment. Students will be able to view this report along with their results.
                            </div>

                            <div class="mb-3">
                                <label for="report_title" class="form-label">Report Title <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control @error('report_title') is-invalid @enderror" 
                                    id="report_title" 
                                    name="report_title" 
                                    value="{{ old('report_title', $appointment->appointment_title . ' - Report') }}" 
                                    required
                                >
                                @error('report_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="report_content" class="form-label">Report Content <span class="text-danger">*</span></label>
                                <textarea 
                                    class="form-control @error('report_content') is-invalid @enderror" 
                                    id="report_content" 
                                    name="report_content" 
                                    rows="15" 
                                    required
                                    placeholder="Write your detailed explanation here..."
                                >{{ old('report_content') }}</textarea>
                                <small class="text-muted">Write a detailed explanation of the appointment, what was covered, overall assessment, and any notes for students.</small>
                                @error('report_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Create Report
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Student Results Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Student Results</h5>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Quiz Score</th>
                                        <th>Project Submitted</th>
                                        <th>Attendance</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        @php
                                            $quizAttempt = $quizAttempts[$student->user_id] ?? null;
                                            $projects = $projectSubmissions[$student->user_id] ?? collect();
                                            $attended = $attendance[$student->user_id] ?? null;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $student->full_name }}</strong>
                                                <br><small class="text-muted">{{ $student->username }}</small>
                                            </td>
                                            <td>
                                                @if($quizAttempt)
                                                    <span class="badge bg-{{ $quizAttempt->passed ? 'success' : 'warning' }}">
                                                        {{ number_format($quizAttempt->score, 1) }}/{{ number_format($quizAttempt->total_points, 1) }}
                                                        ({{ $quizAttempt->passed ? 'Passed' : 'Failed' }})
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not taken</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($projects->count() > 0)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> {{ $projects->count() }} file(s)
                                                    </span>
                                                    <div class="mt-1">
                                                        @foreach($projects as $project)
                                                            <a href="{{ $project->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary me-1 mb-1">
                                                                <i class="bi bi-download"></i> {{ Str::limit($project->file_name, 20) }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">No submission</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attended)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Present
                                                    </span>
                                                    @if($attended->duration_minutes)
                                                        <br><small class="text-muted">{{ $attended->duration_minutes }} mins</small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Absent</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $student->email }}" class="btn btn-sm btn-outline-primary" title="Email Student">
                                                    <i class="bi bi-envelope"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Students Enrolled</h5>
                            <p class="text-muted">Students need to join the classroom first.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleEditMode() {
    const viewMode = document.getElementById('report-view');
    const editMode = document.getElementById('report-edit');
    
    if (viewMode.style.display === 'none') {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
    } else {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    }
}
</script>
@endsection