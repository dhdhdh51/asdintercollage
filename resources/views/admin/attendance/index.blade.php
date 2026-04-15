@extends('layouts.app')
@section('title', 'Attendance')
@section('page-title', 'Manage Attendance')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-primary">Mark Attendance</a>
    <a href="{{ route('admin.attendance.report') }}" class="btn btn-outline-primary">Attendance Report</a>
</div>
<div class="form-card mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Class *</label>
            <select name="class_id" class="form-select">
                <option value="">Select Class...</option>
                @foreach($classes as $class)<option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>Class {{ $class->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Section</label>
            <select name="section_id" class="form-select">
                <option value="">All Sections</option>
                @foreach($sections as $section)<option value="{{ $section->id }}" {{ $sectionId == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" max="{{ today()->format('Y-m-d') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Load</button>
        </div>
    </form>
</div>
@if($students->count() > 0)
<div class="form-card">
    <form method="POST" action="{{ route('admin.attendance.store') }}">
        @csrf
        <input type="hidden" name="class_id" value="{{ $classId }}">
        <input type="hidden" name="section_id" value="{{ $sectionId }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <div class="table-card-header mb-3">
            <h6 class="table-card-title">{{ $students->count() }} Students</h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-success" onclick="markAll('present')">All Present</button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="markAll('absent')">All Absent</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Student</th><th>Present</th><th>Absent</th><th>Late</th></tr></thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td><div class="fw-semibold">{{ $student->user->name }}</div><div class="text-muted small">{{ $student->student_id }}</div></td>
                        @foreach(['present','absent','late'] as $status)
                        <td><input class="form-check-input att-radio" type="radio" name="attendance[{{ $student->id }}]" value="{{ $status }}" {{ ($attendances[$student->id]->status ?? 'present') === $status ? 'checked' : '' }}></td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary mt-2"><i class="bi bi-save me-2"></i>Save</button>
    </form>
</div>
@elseif($classId)
<div class="alert alert-info">No students found.</div>
@endif
@endsection
@push('scripts')
<script>function markAll(s){document.querySelectorAll('.att-radio[value="'+s+'"]').forEach(r=>r.checked=true);}</script>
@endpush
