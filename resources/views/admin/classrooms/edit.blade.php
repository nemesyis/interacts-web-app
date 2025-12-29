@extends('layouts.admin')

@section('title', 'Edit Classroom')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.classrooms') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Classrooms
            </a>
            <h1 class="h3 mb-0">Edit Classroom</h1>
            <p class="text-muted">Update classroom details</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.classrooms.update', $classroom->classroom_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="classroom_name" class="form-label">Classroom Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('classroom_name') is-invalid @enderror" 
                                id="classroom_name" 
                                name="classroom_name" 
                                value="{{ old('classroom_name', $classroom->classroom_name) }}" 
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
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->user_id }}" 
                                        {{ old('teacher_id', $classroom->teacher_id) == $teacher->user_id ? 'selected' : '' }}>
                                        {{ $teacher->full_name }} ({{ $teacher->username }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
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
                            >{{ old('description', $classroom->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Classroom
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
            <!-- Classroom Info -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Classroom Information</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Access Token:</td>
                            <td><code>{{ $classroom->access_token }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Token Status:</td>
                            <td>
                                <span class="badge bg-{{ $classroom->token_is_active ? 'success' : 'secondary' }}">
                                    {{ $classroom->token_is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Created:</td>
                            <td>{{ $classroom->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Created By:</td>
                            <td>{{ $classroom->admin->full_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Enrolled Students -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Enrolled Students</h5>
                    @if($classroom->enrollments->where('status', 'active')->count() > 0)
                        <p class="text-muted small mb-2">{{ $classroom->enrollments->where('status', 'active')->count() }} active students</p>
                        <div class="list-group list-group-flush">
                            @foreach($classroom->enrollments->where('status', 'active')->take(5) as $enrollment)
                                <div class="list-group-item px-0 py-2">
                                    <i class="bi bi-person-circle me-2"></i>
                                    {{ $enrollment->student->full_name }}
                                </div>
                            @endforeach
                            @if($classroom->enrollments->where('status', 'active')->count() > 5)
                                <div class="list-group-item px-0 py-2 text-muted">
                                    <small>+ {{ $classroom->enrollments->where('status', 'active')->count() - 5 }} more</small>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mb-0">No students enrolled yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection