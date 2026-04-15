@extends('layouts.app')
@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')
@section('sidebar-menu')
    <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('teacher.attendance') }}" class="nav-link {{ request()->routeIs('teacher.attendance') ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> Mark Attendance</a>
    <a href="{{ route('teacher.homeworks') }}" class="nav-link {{ request()->routeIs('teacher.homeworks') ? 'active' : '' }}"><i class="bi bi-journal-plus"></i> Homeworks</a>
@endsection
@section('content')
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="p-4 rounded-3" style="background:linear-gradient(135deg,#06b6d4,#4f46e5);color:white;">
            <h4 class="mb-0 fw-bold">Hello, {{ $teacher->user->name }}!</h4>
            <p class="mb-0 opacity-75">{{ $teacher->employee_id }} | {{ $teacher->specialization ?? $teacher->qualification ?? 'Teacher' }}</p>
        </div>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card"><div class="stat-card-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-grid"></i></div><div><div class="stat-card-value">{{ $assignedClasses }}</div><div class="stat-card-label">Classes</div></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card"><div class="stat-card-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-book"></i></div><div><div class="stat-card-value">{{ $assignedSubjects }}</div><div class="stat-card-label">Subjects</div></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card"><div class="stat-card-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-journal-text"></i></div><div><div class="stat-card-value">{{ $recentHomeworks->count() }}</div><div class="stat-card-label">Recent HW</div></div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="table-card">
            <div class="table-card-header"><h6 class="table-card-title">My Classes & Subjects</h6></div>
            <div class="list-group list-group-flush">
                @forelse($teacher->classSubjects as $cs)
                <div class="list-group-item px-3 py-2">
                    <div class="fw-semibold small">Class {{ $cs->class->name ?? 'N/A' }} {{ $cs->section ? '- ' . $cs->section->name : '' }}</div>
                    <div class="text-muted small">{{ $cs->subject->name ?? 'N/A' }}</div>
                </div>
                @empty
                <div class="p-3 text-center text-muted small">No assignments yet</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="table-card">
            <div class="table-card-header"><h6 class="table-card-title">Announcements</h6></div>
            <div class="list-group list-group-flush">
                @forelse($notifications as $notif)
                <div class="list-group-item px-3 py-2">
                    <div class="fw-semibold small">{{ $notif->title }}</div>
                    <div class="text-muted small">{{ Str::limit($notif->message, 70) }}</div>
                </div>
                @empty
                <div class="p-3 text-center text-muted small">No announcements</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
