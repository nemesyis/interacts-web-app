@extends('layouts.admin')

@section('title', 'Invite Teacher')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Invite Teacher</h1>
            <p class="text-muted">Send invitation to new teachers</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">New Invitation</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.invite.teacher.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="teacher_full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('teacher_full_name') is-invalid @enderror" 
                                id="teacher_full_name" 
                                name="teacher_full_name" 
                                value="{{ old('teacher_full_name') }}" 
                                required
                            >
                            @error('teacher_full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="teacher_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input 
                                type="email" 
                                class="form-control @error('teacher_email') is-invalid @enderror" 
                                id="teacher_email" 
                                name="teacher_email" 
                                value="{{ old('teacher_email') }}" 
                                required
                            >
                            @error('teacher_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="teacher_username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('teacher_username') is-invalid @enderror" 
                                id="teacher_username" 
                                name="teacher_username" 
                                value="{{ old('teacher_username') }}" 
                                required
                            >
                            <small class="text-muted">Letters, numbers, dashes and underscores only</small>
                            @error('teacher_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>A temporary password will be generated and sent via email. The teacher must change it on first login.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Send Invitation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Invitation History</h5>
                </div>
                <div class="card-body">
                    @if($invitations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Sent</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invitations as $invitation)
                                        <tr>
                                            <td>{{ $invitation->teacher_full_name }}</td>
                                            <td>{{ $invitation->teacher_email }}</td>
                                            <td><code>{{ $invitation->teacher_username }}</code></td>
                                            <td>
                                                @if($invitation->status === 'pending' || $invitation->status === 'resent')
                                                    <span class="badge bg-warning">
                                                        {{ ucfirst($invitation->status) }}
                                                    </span>
                                                @elseif($invitation->status === 'accepted')
                                                    <span class="badge bg-success">Accepted</span>
                                                @elseif($invitation->status === 'expired')
                                                    <span class="badge bg-danger">Expired</span>
                                                @endif
                                            </td>
                                            <td>{{ $invitation->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($invitation->status !== 'accepted')
                                                    <form method="POST" action="{{ route('admin.invitation.resend', $invitation->invitation_id) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Resend Invitation">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $invitations->links() }}
                        </div>
                    @else
                        <p class="text-muted text-center py-4 mb-0">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No invitations sent yet
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection