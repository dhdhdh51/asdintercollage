@extends('layouts.app')
@section('title', 'Teachers')
@section('page-title', 'Teachers')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">Teachers ({{ $teachers->total() }})</h4>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Teacher</a>
</div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Employee ID</th><th>Name</th><th>Email</th><th>Qualification</th><th>Joining Date</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($teachers as $teacher)
                <tr>
                    <td><code>{{ $teacher->employee_id }}</code></td>
                    <td class="fw-semibold">{{ $teacher->user->name }}</td>
                    <td>{{ $teacher->user->email }}</td>
                    <td>{{ $teacher->qualification ?? 'N/A' }}</td>
                    <td class="text-muted small">{{ $teacher->joining_date ? $teacher->joining_date->format('d M Y') : 'N/A' }}</td>
                    <td><span class="badge {{ $teacher->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} badge-status">{{ $teacher->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No teachers found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">{{ $teachers->links() }}</div>
</div>
@endsection
