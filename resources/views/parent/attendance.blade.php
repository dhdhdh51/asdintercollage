@extends('layouts.app')
@section('title', 'Attendance')
@section('page-title', "Attendance - {{ $student->user->name }}")
@section('sidebar-menu')
    <a href="{{ route('parent.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('parent.children') }}" class="nav-link active"><i class="bi bi-people"></i> My Children</a>
@endsection
@section('content')
<div class="row g-2 mb-3">
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-calendar"></i></div><div><div class="stat-card-value">{{ $stats['total'] }}</div><div class="stat-card-label">Total</div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-check-circle"></i></div><div><div class="stat-card-value">{{ $stats['present'] }}</div><div class="stat-card-label">Present</div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-x-circle"></i></div><div><div class="stat-card-value">{{ $stats['absent'] }}</div><div class="stat-card-label">Absent</div></div></div></div>
</div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Date</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($attendances as $att)
                <tr><td>{{ $att->date->format('d M Y') }}</td><td><span class="badge bg-{{ $att->status === 'present' ? 'success' : 'danger' }}-subtle text-{{ $att->status === 'present' ? 'success' : 'danger' }} badge-status">{{ ucfirst($att->status) }}</span></td></tr>
                @empty
                <tr><td colspan="2" class="text-center py-4 text-muted">No records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">{{ $attendances->links() }}</div>
</div>
@endsection
