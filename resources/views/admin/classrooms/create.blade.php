@extends('layouts.admin')

@section('title', 'Create Classroom')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.classrooms') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Classrooms
            </a>
            <h1 class="h3 mb-0">Create New Classroom</h1>
            <p class="text-muted">Set up a new classroom and assign a teacher</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.classrooms.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="classroom_name" class="form-label">Classroom Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('classroom_name') is-invalid @enderror" 
                                id="classroom_name" 
                                name="classroom_name" 
                                value="{{ old('classroom_name') }}" 
                                placeholder="e.g. Mathematics 101"
                                required
                            >
                            @error('classroom_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="teacher_id" class="form-label">Assign Teacher <span class="text-danger">*</span></label>
                            <select 
                                class="form-select @error('teacher_id') is-invalid @enderror" 
                                id="teacher_id" 
                                name="teacher_id" 
                                required
                            >
                                <option value="">-- Select Teacher --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->user_id }}" {{ old('teacher_id') == $teacher->user_id ? 'selected' : '' }}>
                                        {{ $teacher->full_name }} ({{ $teacher->username }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($teachers->count() == 0)
                                <div class="alert alert-warning mt-2 mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No teachers available. Please <a href="{{ route('admin.invite.teacher') }}">invite a teacher</a> first.
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description <span class="text-muted">(Optional)</span></label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="4"
                                placeholder="Brief description of the classroom..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> An access token will be automatically generated for this classroom. The token will be inactive by default - you can activate it from the classrooms list.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" {{ $teachers->count() == 0 ? 'disabled' : '' }}>
                                <i class="bi bi-check-circle me-2"></i>Create Classroom
                            </button>
                            <a href="{{ route('admin.classrooms') }}" class="btn btn-outline-secondary">
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
                        <li class="mb-2">Choose a clear, descriptive name for the classroom</li>
                        <li class="mb-2">Make sure the assigned teacher has accepted their invitation</li>
                        <li class="mb-2">The access token can be shared with students to join the classroom</li>
                        <li class="mb-2">You can activate/deactivate the token anytime to control student enrollment</li>
                        <li>Students can only join when the token is active</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection