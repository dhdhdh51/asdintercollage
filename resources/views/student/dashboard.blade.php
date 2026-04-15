@extends('layouts.app')
@section('title', 'Student Dashboard')
@section('page-title', 'Student Dashboard')
@section('sidebar-menu')
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
        <i class="bi bi-person"></i> My Profile
    </a>
    <a href="{{ route('student.attendance') }}" class="nav-link {{ request()->routeIs('student.attendance') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Attendance
    </a>
    <a href="{{ route('student.fees') }}" class="nav-link {{ request()->routeIs('student.fees') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i> Fee & Payments
    </a>
    <a href="{{ route('admission.status') }}" class="nav-link">
        <i class="bi bi-file-earmark-person"></i> Admission Status
    </a>
@endsection
@section('content')
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="p-4 rounded-3" style="background:linear-gradient(135deg,#4f46e5,#06b6d4);color:white;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:60px;height:60px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;">
                    {{ strtoupper(substr($student->user->name, 0, 1)) }}
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Hello, {{ $student->user->name }}!</h4>
                    <p class="mb-0 opacity-75">Class {{ $student->class->name ?? 'N/A' }} {{ $student->section ? '| Section ' . $student->section->name : '' }} | {{ $student->student_id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-calendar-check"></i></div>
            <div><div class="stat-card-value">{{ $attendancePercent }}%</div><div class="stat-card-label">Attendance</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-check-circle"></i></div>
            <div><div class="stat-card-value">{{ $presentDays }}</div><div class="stat-card-label">Days Present</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-cash-coin"></i></div>
            <div><div class="stat-card-value">₹{{ number_format($paidFees) }}</div><div class="stat-card-label">Fee Paid</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-exclamation-circle"></i></div>
            <div><div class="stat-card-value">₹{{ number_format($pendingFees) }}</div><div class="stat-card-label">Fee Pending</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Upcoming Homeworks -->
    <div class="col-md-6">
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="table-card-title"><i class="bi bi-journal-text me-2"></i>Homework & Assignments</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($homeworks as $hw)
                <div class="list-group-item px-3 py-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold small">{{ $hw->title }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $hw->subject->name ?? '' }}</div>
                        </div>
                        <span class="badge {{ $hw->due_date->isPast() ? 'bg-danger' : 'bg-warning text-dark' }} badge-status">
                            Due: {{ $hw->due_date->format('d M') }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-3 text-center text-muted small"><i class="bi bi-journal-check d-block mb-1" style="font-size:1.5rem;"></i>No pending homework!</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="col-md-6">
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="table-card-title"><i class="bi bi-bell me-2"></i>Latest Announcements</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($notifications as $notif)
                <div class="list-group-item px-3 py-2">
                    <div class="d-flex gap-2 align-items-start">
                        <span class="badge bg-{{ $notif->type }}-subtle text-{{ $notif->type }}" style="font-size:0.65rem;margin-top:2px;">{{ strtoupper($notif->type) }}</span>
                        <div>
                            <div class="fw-semibold small">{{ $notif->title }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ Str::limit($notif->message, 80) }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-3 text-center text-muted small">No announcements</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
