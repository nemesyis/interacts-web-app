@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="container-fluid py-4">
    <style>
        :root {
            --primary-blue: #00adccff;
            --primary-pink: #ff1493;
            --light-blue: #e6f2ff;
            --light-pink: #ffe6f0;
            --accent-orange: #ff9500;
            --accent-green: #00d084;
            --accent-purple: #7c3aed;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-pink));
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            border-radius: 20px;
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        /* Statistics Cards */
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 1.8rem;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            opacity: 0.1;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        }

        .stat-card.card-blue::before {
            background: var(--primary-blue);
        }

        .stat-card.card-pink::before {
            background: var(--primary-pink);
        }

        .stat-card.card-orange::before {
            background: var(--accent-orange);
        }

        .stat-card.card-green::before {
            background: var(--accent-green);
        }

        .stat-card-content {
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.8rem;
        }

        .stat-card.card-blue .stat-label {
            color: var(--primary-blue);
        }

        .stat-card.card-pink .stat-label {
            color: var(--primary-pink);
        }

        .stat-card.card-orange .stat-label {
            color: var(--accent-orange);
        }

        .stat-card.card-green .stat-label {
            color: var(--accent-green);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .stat-card.card-blue .stat-number {
            color: var(--primary-blue);
        }

        .stat-card.card-pink .stat-number {
            color: var(--primary-pink);
        }

        .stat-card.card-orange .stat-number {
            color: var(--accent-orange);
        }

        .stat-card.card-green .stat-number {
            color: var(--accent-green);
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        /* Section Headers */
        .section-header {
            margin-top: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .section-header h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .section-header.blue .section-header-icon {
            background: rgba(0, 173, 204, 0.1);
            color: var(--primary-blue);
        }

        .section-header.pink .section-header-icon {
            background: rgba(255, 20, 147, 0.1);
            color: var(--primary-pink);
        }

        .section-header.green .section-header-icon {
            background: rgba(0, 208, 132, 0.1);
            color: var(--accent-green);
        }

        /* Classroom Card */
        .classroom-card {
            border: 2px solid #e8e8e8;
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .classroom-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-pink));
        }

        .classroom-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 102, 204, 0.15);
            border-color: var(--primary-blue);
        }

        .classroom-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.8rem;
        }

        .classroom-teacher {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .classroom-badge-group {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
        }

        .classroom-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }

        .classroom-badge.blue {
            background: rgba(0, 173, 204, 0.1);
            color: var(--primary-blue);
        }

        .classroom-badge.green {
            background: rgba(0, 208, 132, 0.1);
            color: var(--accent-green);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, rgba(0, 173, 204, 0.05) 0%, rgba(255, 20, 147, 0.05) 100%);
            border-radius: 16px;
        }

        .empty-state-icon {
            font-size: 3.5rem;
            color: var(--primary-blue);
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .empty-state h5 {
            color: #333;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .btn-join {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-join:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 173, 204, 0.3);
            color: white;
        }

        .btn-view-classroom {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view-classroom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 173, 204, 0.25);
            color: white;
        }

        .btn-join-new {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-join-new:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 173, 204, 0.3);
            color: white;
        }

        .appointments-section {
            margin-top: 2.5rem;
        }

        .appointment-item {
            border-left: 4px solid var(--primary-blue);
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .appointment-item:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 20px rgba(0, 102, 204, 0.1);
        }

        .appointment-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .appointment-meta {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.8rem;
        }

        .appointment-badge {
            display: inline-block;
            background: rgba(0, 208, 132, 0.1);
            color: var(--accent-green);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 0.8rem;
        }

        .appointment-button {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .appointment-button:hover {
            background: var(--primary-pink);
            transform: translateY(-2px);
        }

        .btn-view-classroom {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
    </style>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1>Welcome, {{ auth()->user()->full_name }}! 👋</h1>
                <p>You're doing great! Keep learning and growing every day.</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card card-blue">
                <div class="stat-icon">
                    <i class="bi bi-door-open"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label"><i class="bi bi-door-open me-1"></i>My Classrooms</div>
                    <div class="stat-number">{{ $stats['total_classrooms'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card card-pink">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label"><i class="bi bi-calendar-check me-1"></i>Appointments</div>
                    <div class="stat-number">{{ $stats['total_appointments'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card card-orange">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label"><i class="bi bi-check-circle me-1"></i>Quizzes Done</div>
                    <div class="stat-number">{{ $stats['completed_quizzes'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card card-green">
                <div class="stat-icon">
                    <i class="bi bi-file-earmark"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label"><i class="bi bi-file-earmark me-1"></i>Projects</div>
                    <div class="stat-number">{{ $stats['submitted_projects'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Classrooms Section -->
    <div class="section-header blue">
        <div class="section-header-icon">
            <i class="bi bi-door-open"></i>
        </div>
        <h3>My Classrooms</h3>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                <a href="{{ route('student.join') }}" class="btn btn-join-new">
                    <i class="bi bi-plus-circle me-2"></i>Join New Classroom
                </a>
            </div>

            @if($enrollments->count() > 0)
                <div class="row g-3">
                    @foreach($enrollments as $enrollment)
                        <div class="col-md-6 col-lg-4">
                            <div class="classroom-card">
                                <div class="classroom-title">
                                    <i class="bi bi-book me-2" style="color: var(--primary-blue);"></i>
                                    {{ $enrollment->classroom->classroom_name }}
                                </div>
                                <div class="classroom-teacher">
                                    <i class="bi bi-person-badge me-1"></i>
                                    {{ $enrollment->classroom->teacher->full_name }}
                                </div>
                                <div class="classroom-badge-group">
                                    <span class="classroom-badge blue">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $enrollment->classroom->appointments->count() }} appointments
                                    </span>
                                    <span class="classroom-badge green">
                                        <i class="bi bi-clock me-1"></i>
                                        Joined {{ $enrollment->enrolled_at->diffForHumans() }}
                                    </span>
                                </div>
                                <a href="{{ route('student.classroom.view', $enrollment->classroom_id) }}" class="btn-view-classroom">
                                    <i class="bi-arrow-right-circle"></i> View Classroom
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h5>No Classrooms Yet</h5>
                    <p>Join your first classroom using an access token to get started!</p>
                    <a href="{{ route('student.join') }}" class="btn btn-join">
                        <i class="bi bi-plus-circle me-2"></i>Join Your First Classroom
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Open Appointments Section -->
    @if($upcomingAppointments->count() > 0)
        <div class="appointments-section">
            <div class="section-header pink">
                <div class="section-header-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3>Open Appointments</h3>
            </div>

            <div class="row">
                <div class="col-12">
                    @foreach($upcomingAppointments as $appointment)
                        <div class="appointment-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div style="flex: 1;">
                                    <div class="appointment-title">
                                        <i class="bi bi-calendar-event me-2"></i>{{ $appointment->appointment_title }}
                                    </div>
                                    <div class="appointment-meta">
                                        <i class="bi bi-door-open me-1"></i>
                                        <strong>{{ $appointment->classroom->classroom_name }}</strong>
                                    </div>
                                    <div class="appointment-meta">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $appointment->scheduled_date->format('M d, Y') }} at {{ date('g:i A', strtotime($appointment->scheduled_time)) }}
                                    </div>
                                    <div>
                                        <span class="appointment-badge">
                                            <i class="bi bi-play-circle me-1"></i>Open
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('student.appointment.view', $appointment->appointment_id) }}" class="appointment-button">
                                        <i class="bi bi-arrow-right me-1"></i>View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection