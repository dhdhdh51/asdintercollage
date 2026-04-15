@extends('layouts.app')
@section('title', 'Attendance')
@section('page-title', 'My Attendance')
@section('sidebar-menu')
    <a href="{{ route('student.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('student.profile') }}" class="nav-link"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('student.attendance') }}" class="nav-link active"><i class="bi bi-calendar-check"></i> Attendance</a>
    <a href="{{ route('student.fees') }}" class="nav-link"><i class="bi bi-cash-stack"></i> Fee & Payments</a>
@endsection
@section('content')
<div class="row g-3 mb-3">
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-calendar"></i></div><div><div class="stat-card-value">{{ $stats['total'] }}</div><div class="stat-card-label">Total Days</div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-check-circle"></i></div><div><div class="stat-card-value">{{ $stats['present'] }}</div><div class="stat-card-label">Present</div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-x-circle"></i></div><div><div class="stat-card-value">{{ $stats['absent'] }}</div><div class="stat-card-label">Absent</div></div></div></div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Date</th><th>Subject</th><th>Status</th><th>Remarks</th></tr></thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>{{ $att->date->format('d M Y') }}</td>
                    <td>{{ $att->subject->name ?? 'General' }}</td>
                    <td>
                        <span class="badge bg-{{ $att->status === 'present' ? 'success' : ($att->status === 'absent' ? 'danger' : 'warning') }}-subtle text-{{ $att->status === 'present' ? 'success' : ($att->status === 'absent' ? 'danger' : 'warning') }} badge-status">
                            {{ ucfirst($att->status) }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $att->remarks ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">No attendance records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">{{ $attendances->links() }}</div>
</div>
@endsection
