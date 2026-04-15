@extends('layouts.app')
@section('title', 'Sections')
@section('page-title', 'Class ' . $class->name . ' - Sections')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="form-card">
            <h6 class="fw-bold mb-3">Add Section</h6>
            <form method="POST" action="{{ route('admin.classes.sections.store', $class) }}">
                @csrf
                <div class="mb-3"><label class="form-label small">Section Name *</label><input type="text" name="name" class="form-control" placeholder="e.g. A, B, C" required></div>
                <div class="mb-3"><label class="form-label small">Capacity</label><input type="number" name="capacity" class="form-control" value="40" min="1"></div>
                <button type="submit" class="btn btn-primary w-100">Add Section</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="table-card">
            <div class="table-card-header"><h6 class="table-card-title">Sections in Class {{ $class->name }}</h6></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Section</th><th>Capacity</th><th>Students</th></tr></thead>
                    <tbody>
                        @forelse($sections as $section)
                        <tr><td class="fw-semibold">Section {{ $section->name }}</td><td>{{ $section->capacity }}</td><td>{{ $section->students_count }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No sections</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
