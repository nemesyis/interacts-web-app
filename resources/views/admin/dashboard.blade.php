@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Admin Dashboard</h1>
            <p class="text-muted">Welcome back, {{ auth()->user()->full_name }}!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Teachers</p>
                            <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-person-badge text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Students</p>
                            <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-people text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Classrooms</p>
                            <h3 class="mb-0">{{ $stats['total_classrooms'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-door-open text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pending Invitations</p>
                            <h3 class="mb-0">{{ $stats['pending_invitations'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="bi bi-envelope text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.invite.teacher') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>Invite Teacher
                        </a>
                        <a href="{{ route('admin.classrooms.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Create Classroom
                        </a>
                        <a href="{{ route('admin.classrooms') }}" class="btn btn-info">
                            <i class="bi bi-list-ul me-2"></i>Manage Classrooms
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Invitations</h5>
                    @if($recentInvitations->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentInvitations as $invitation)
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $invitation->teacher_full_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $invitation->teacher_email }}</small>
                                        </div>
                                        <span class="badge bg-{{ $invitation->status === 'pending' ? 'warning' : ($invitation->status === 'accepted' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($invitation->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No invitations yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Classrooms -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Classrooms</h5>
                    @if($recentClassrooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Classroom Name</th>
                                        <th>Teacher</th>
                                        <th>Access Token</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentClassrooms as $classroom)
                                        <tr>
                                            <td>
                                                <strong>{{ $classroom->classroom_name }}</strong>
                                            </td>
                                            <td>{{ $classroom->teacher->full_name }}</td>
                                            <td>
                                                <code>{{ $classroom->access_token }}</code>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $classroom->token_is_active ? 'success' : 'secondary' }}">
                                                    {{ $classroom->token_is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $classroom->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No classrooms created yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection