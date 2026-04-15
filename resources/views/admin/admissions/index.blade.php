@extends('layouts.app')
@section('title', 'Admissions')
@section('page-title', 'Admissions Management')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<!-- Stats -->
<div class="row g-2 mb-3">
    <div class="col-3"><div class="stat-card"><div class="stat-card-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-file-earmark-text"></i></div><div><div class="stat-card-value">{{ $counts['all'] }}</div><div class="stat-card-label">Total</div></div></div></div>
    <div class="col-3"><div class="stat-card"><div class="stat-card-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-hourglass-split"></i></div><div><div class="stat-card-value">{{ $counts['pending'] }}</div><div class="stat-card-label">Pending</div></div></div></div>
    <div class="col-3"><div class="stat-card"><div class="stat-card-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-check-circle"></i></div><div><div class="stat-card-value">{{ $counts['approved'] }}</div><div class="stat-card-label">Approved</div></div></div></div>
    <div class="col-3"><div class="stat-card"><div class="stat-card-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-x-circle"></i></div><div><div class="stat-card-value">{{ $counts['rejected'] }}</div><div class="stat-card-label">Rejected</div></div></div></div>
</div>

<!-- Filters -->
<div class="form-card mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, App ID, phone..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="class_id" class="form-select form-select-sm">
                <option value="">All Classes</option>
                @foreach($classes as $class)<option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>Class {{ $class->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('admin.admissions.index') }}" class="btn btn-sm btn-light ms-1">Reset</a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>App ID</th><th>Student Name</th><th>Father Name</th><th>Class</th><th>Phone</th><th>Applied On</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($admissions as $admission)
                <tr>
                    <td><code>{{ $admission->application_id }}</code></td>
                    <td class="fw-semibold">{{ $admission->student_name }}</td>
                    <td>{{ $admission->father_name }}</td>
                    <td><span class="badge bg-primary-subtle text-primary">Class {{ $admission->class->name ?? 'N/A' }}</span></td>
                    <td>{{ $admission->phone }}</td>
                    <td class="text-muted small">{{ $admission->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $admission->status_badge }}-subtle text-{{ $admission->status_badge }} badge-status">
                            {{ ucfirst($admission->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.admissions.show', $admission) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No admissions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">{{ $admissions->withQueryString()->links() }}</div>
</div>
@endsection
