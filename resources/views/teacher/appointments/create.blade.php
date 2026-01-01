@extends('layouts.teacher')

@section('title', 'Create Appointment')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('teacher.appointments', $classroom->classroom_id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Appointments
            </a>
            <h1 class="h3 mb-0">Create New Appointment</h1>
            <p class="text-muted">{{ $classroom->classroom_name }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('teacher.appointments.store', $classroom->classroom_id) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="appointment_title" class="form-label">Appointment Title <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('appointment_title') is-invalid @enderror" 
                                id="appointment_title" 
                                name="appointment_title" 
                                value="{{ old('appointment_title') }}" 
                                placeholder="e.g. Introduction to Algebra"
                                required
                            >
                            @error('appointment_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description <span class="text-muted">(Optional)</span></label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="4"
                                placeholder="Brief description of what will be covered in this appointment..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="scheduled_date" class="form-label">Scheduled Date <span class="text-danger">*</span></label>
                                <input 
                                    type="date" 
                                    class="form-control @error('scheduled_date') is-invalid @enderror" 
                                    id="scheduled_date" 
                                    name="scheduled_date" 
                                    value="{{ old('scheduled_date', date('Y-m-d')) }}" 
                                    required
                                >
                                @error('scheduled_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="scheduled_time" class="form-label">Scheduled Time <span class="text-danger">*</span></label>
                                <input 
                                    type="time" 
                                    class="form-control @error('scheduled_time') is-invalid @enderror" 
                                    id="scheduled_time" 
                                    name="scheduled_time" 
                                    value="{{ old('scheduled_time', '09:00') }}" 
                                    required
                                >
                                @error('scheduled_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="duration_minutes" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                            <select 
                                class="form-select @error('duration_minutes') is-invalid @enderror" 
                                id="duration_minutes" 
                                name="duration_minutes" 
                                required
                            >
                                <option value="30" {{ old('duration_minutes') == 30 ? 'selected' : '' }}>30 minutes</option>
                                <option value="45" {{ old('duration_minutes') == 45 ? 'selected' : '' }}>45 minutes</option>
                                <option value="60" {{ old('duration_minutes', 60) == 60 ? 'selected' : '' }}>1 hour</option>
                                <option value="90" {{ old('duration_minutes') == 90 ? 'selected' : '' }}>1.5 hours</option>
                                <option value="120" {{ old('duration_minutes') == 120 ? 'selected' : '' }}>2 hours</option>
                                <option value="180" {{ old('duration_minutes') == 180 ? 'selected' : '' }}>3 hours</option>
                            </select>
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> This will be Appointment #{{ $nextNumber }} in this classroom. The appointment will be created as "Closed" - you can open it when ready from the appointments list.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Create Appointment
                            </button>
                            <a href="{{ route('teacher.appointments', $classroom->classroom_id) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips
                    </h5>
                    <ul class="small mb-0">
                        <li class="mb-2">Choose a clear, descriptive title for the appointment</li>
                        <li class="mb-2">Schedule the date and time when students should attend</li>
                        <li class="mb-2">After creating, you can:
                            <ul class="mt-1">
                                <li>Add materials (videos, PDFs, links)</li>
                                <li>Create a quiz</li>
                                <li>Create project assignments</li>
                                <li>Write a report after the session</li>
                            </ul>
                        </li>
                        <li class="mb-2">Open the appointment when you're ready for students to access it</li>
                        <li>Students can only submit work when the appointment is open</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection