@extends('layouts.app')
@section('title', 'Students')
@section('page-title', 'Students')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="page-header">
    <h4 class="page-title"><i class="bi bi-people me-2"></i>Students ({{ $students->total() }})</h4>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Student
    </a>
</div>

<!-- Filters -->
<div class="form-card mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small">Search</label>
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="Name, ID, email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Filter by Class</label>
            <select name="class_id" class="form-select form-select-sm">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        Class {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-light ms-1">Reset</a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Father Name</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td><code>{{ $student->student_id }}</code></td>
                    <td>
                        <div class="fw-semibold">{{ $student->user->name }}</div>
                        <div class="text-muted small">{{ $student->user->email }}</div>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary">Class {{ $student->class->name ?? 'N/A' }}</span>
                        @if($student->section) <span class="badge bg-light text-dark">Sec {{ $student->section->name }}</span> @endif
                    </td>
                    <td>{{ $student->father_name }}</td>
                    <td class="text-muted small">{{ $student->user->phone ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $student->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} badge-status">
                            {{ $student->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-light" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                                onsubmit="return confirm('Deactivate this student?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Deactivate">
                                    <i class="bi bi-person-x"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">
                    <i class="bi bi-people display-4 d-block mb-2 opacity-25"></i>
                    No students found
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">
        {{ $students->withQueryString()->links() }}
    </div>
</div>
@endsection
