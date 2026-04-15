@extends('layouts.app')
@section('title', 'Parent Dashboard')
@section('page-title', 'Parent Dashboard')
@section('sidebar-menu')
    <a href="{{ route('parent.dashboard') }}" class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('parent.children') }}" class="nav-link {{ request()->routeIs('parent.children') ? 'active' : '' }}"><i class="bi bi-people"></i> My Children</a>
@endsection
@section('content')
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="p-4 rounded-3" style="background:linear-gradient(135deg,#10b981,#06b6d4);color:white;">
            <h4 class="mb-0 fw-bold">Welcome, {{ $parent->user->name }}!</h4>
            <p class="mb-0 opacity-75">{{ $parent->students->count() }} child(ren) enrolled</p>
        </div>
    </div>
</div>
<div class="row g-3">
    @foreach($parent->students as $student)
    <div class="col-md-6">
        <div class="form-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:50px;height:50px;background:#4f46e5;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.2rem;">
                    {{ strtoupper(substr($student->user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold">{{ $student->user->name }}</div>
                    <div class="text-muted small">{{ $student->student_id }} | Class {{ $student->class->name ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('parent.children.attendance', $student) }}" class="btn btn-sm btn-outline-primary flex-fill">
                    <i class="bi bi-calendar-check me-1"></i>Attendance
                </a>
                <a href="{{ route('parent.children.fees', $student) }}" class="btn btn-sm btn-outline-success flex-fill">
                    <i class="bi bi-cash me-1"></i>Fee Status
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-3">
    <div class="table-card">
        <div class="table-card-header"><h6 class="table-card-title">Announcements</h6></div>
        <div class="list-group list-group-flush">
            @forelse($notifications as $notif)
            <div class="list-group-item px-3 py-2">
                <div class="fw-semibold small">{{ $notif->title }}</div>
                <div class="text-muted small">{{ Str::limit($notif->message, 100) }}</div>
            </div>
            @empty
            <div class="p-3 text-center text-muted small">No announcements</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
