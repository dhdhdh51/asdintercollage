@extends('layouts.app')
@section('title', 'Classes')
@section('page-title', 'Classes & Sections')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="form-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2"></i>Add New Class</h6>
            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf
                <div class="mb-3"><label class="form-label small">Class Name *</label><input type="text" name="name" class="form-control" placeholder="e.g. 1, 2... 12 or One, Two..." required></div>
                <div class="mb-3"><label class="form-label small">Numeric Value *</label><input type="number" name="numeric_value" class="form-control" min="1" max="12" required></div>
                <div class="mb-3"><label class="form-label small">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-2"></i>Add Class</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="table-card">
            <div class="table-card-header"><h6 class="table-card-title">All Classes</h6></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Class</th><th>Students</th><th>Sections</th><th>Subjects</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($classes as $class)
                        <tr>
                            <td class="fw-semibold">Class {{ $class->name }}</td>
                            <td>{{ $class->students_count }}</td>
                            <td>{{ $class->sections_count }}</td>
                            <td>{{ $class->subjects_count }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.classes.sections', $class) }}" class="btn btn-sm btn-outline-primary">Sections</a>
                                    <a href="{{ route('admin.classes.subjects', $class) }}" class="btn btn-sm btn-outline-success">Subjects</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No classes yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top">{{ $classes->links() }}</div>
        </div>
    </div>
</div>
@endsection
