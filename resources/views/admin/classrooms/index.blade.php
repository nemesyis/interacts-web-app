@extends('layouts.admin')

@section('title', 'Manage Classrooms')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Manage Classrooms</h1>
            <p class="text-muted">Create and manage classrooms</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New Classroom
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.classrooms') }}" class="d-flex gap-2">
                <div class="flex-grow-1">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Search by classroom name, teacher name, or student name..."
                        value="{{ $search ?? '' }}"
                    >
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Search
                </button>
                @if($search)
                    <a href="{{ route('admin.classrooms') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    @if($search)
        <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle me-2"></i>
            Search results for: <strong>{{ $search }}</strong>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($classrooms->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Classroom Name</th>
                                <th>Teacher</th>
                                <th>Access Token</th>
                                <th>Students</th>
                                <th>Token Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classrooms as $classroom)
                                <tr>
                                    <td>
                                        <strong>{{ $classroom->classroom_name }}</strong>
                                        @if($classroom->description)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($classroom->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="bi bi-person-badge me-1"></i>
                                        {{ $classroom->teacher->full_name }}
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">{{ $classroom->access_token }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $classroom->enrollments->where('status', 'active')->count() }} students
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.classrooms.toggle.token', $classroom->classroom_id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $classroom->token_is_active ? 'success' : 'secondary' }}">
                                                <i class="bi bi-{{ $classroom->token_is_active ? 'unlock' : 'lock' }} me-1"></i>
                                                {{ $classroom->token_is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $classroom->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.classrooms.edit', $classroom->classroom_id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $classrooms->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    @if($search)
                        <h5 class="text-muted">No Classrooms Found</h5>
                        <p class="text-muted mb-4">No classrooms match your search criteria. Try a different search term.</p>
                        <a href="{{ route('admin.classrooms') }}" class="btn btn-outline-primary">
                            <i class="bi bi-x-circle me-2"></i>Clear Search
                        </a>
                    @else
                        <h5 class="text-muted">No Classrooms Yet</h5>
                        <p class="text-muted mb-4">Create your first classroom to get started</p>
                        <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create Classroom
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection