@extends('layouts.app')
@section('title', 'Subjects')
@section('page-title', 'Class ' . $class->name . ' - Subjects')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="form-card">
            <h6 class="fw-bold mb-3">Add Subject</h6>
            <form method="POST" action="{{ route('admin.classes.subjects.store', $class) }}">
                @csrf
                <div class="mb-3"><label class="form-label small">Subject Name *</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label small">Subject Code *</label><input type="text" name="code" class="form-control" placeholder="e.g. MATH101" required></div>
                <div class="mb-3"><label class="form-label small">Max Marks</label><input type="number" name="max_marks" class="form-control" value="100"></div>
                <div class="mb-3"><label class="form-label small">Pass Marks</label><input type="number" name="pass_marks" class="form-control" value="33"></div>
                <button type="submit" class="btn btn-primary w-100">Add Subject</button>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="table-card">
            <div class="table-card-header"><h6 class="table-card-title">Subjects in Class {{ $class->name }}</h6></div>
            <table class="table mb-0">
                <thead><tr><th>Subject</th><th>Code</th><th>Max Marks</th><th>Pass Marks</th></tr></thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr><td class="fw-semibold">{{ $subject->name }}</td><td><code>{{ $subject->code }}</code></td><td>{{ $subject->max_marks }}</td><td>{{ $subject->pass_marks }}</td></tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No subjects</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
