@extends('layouts.teacher')

@section('title', 'Projects - ' . $appointment->appointment_title)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('teacher.appointments', $appointment->classroom_id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Appointments
            </a>
            <h1 class="h3 mb-0">{{ $appointment->appointment_title }}</h1>
            <p class="text-muted">
                {{ $appointment->classroom->classroom_name }} • 
                Appointment #{{ $appointment->appointment_number }}
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Create Project Form -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Create Project Assignment</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.projects.store', $appointment->appointment_id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="project_title" class="form-label">Project Title <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('project_title') is-invalid @enderror" 
                                id="project_title" 
                                name="project_title" 
                                value="{{ old('project_title') }}" 
                                placeholder="e.g. Final Project - Python Calculator"
                                required
                            >
                            @error('project_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-muted">(Optional)</span></label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="4"
                                placeholder="Describe what students need to do..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date <span class="text-muted">(Optional)</span></label>
                            <input 
                                type="datetime-local" 
                                class="form-control @error('due_date') is-invalid @enderror" 
                                id="due_date" 
                                name="due_date" 
                                value="{{ old('due_date') }}"
                            >
                            <small class="text-muted">Students can only submit while the appointment is open</small>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> Students can submit any file type. Multiple projects can be assigned to one appointment.
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>Create Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Projects List -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Project Assignments</h5>
                </div>
                <div class="card-body">
                    @if($projects->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($projects as $project)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="mb-0">{{ $project->project_title }}</h6>
                                                <span class="badge bg-{{ $project->is_active ? 'success' : 'secondary' }} ms-2">
                                                    {{ $project->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            
                                            @if($project->description)
                                                <p class="text-muted small mb-2">{{ Str::limit($project->description, 100) }}</p>
                                            @endif

                                            <div class="d-flex gap-3 small text-muted">
                                                @if($project->due_date)
                                                    <span>
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Due: {{ $project->due_date->format('M d, Y g:i A') }}
                                                    </span>
                                                @endif
                                                <span>
                                                    <i class="bi bi-file-earmark me-1"></i>
                                                    {{ $project->submissions_count }} submission(s)
                                                </span>
                                            </div>

                                            @if($project->submissions_count > 0)
                                                <div class="mt-2">
                                                    <strong class="small">Recent Submissions:</strong>
                                                    <div class="mt-1">
                                                        @foreach($project->submissions()->latest()->take(3)->get() as $submission)
                                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                                <span class="badge bg-light text-dark">{{ $submission->student->full_name }}</span>
                                                                <a href="{{ $submission->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-download"></i> {{ Str::limit($submission->file_name, 25) }}
                                                                </a>
                                                                <small class="text-muted">{{ $submission->submitted_at->diffForHumans() }}</small>
                                                            </div>
                                                        @endforeach
                                                        @if($project->submissions_count > 3)
                                                            <small class="text-muted">+ {{ $project->submissions_count - 3 }} more</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ms-3">
                                            <form method="POST" action="{{ route('teacher.projects.delete', $project->project_id) }}" class="d-inline" onsubmit="return confirm('Are you sure? This will delete the project and all student submissions!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Project">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-briefcase fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Projects Yet</h5>
                            <p class="text-muted">Create your first project assignment to get started</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection