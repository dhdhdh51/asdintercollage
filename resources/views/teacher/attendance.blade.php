@extends('layouts.app')
@section('title', 'Mark Attendance')
@section('page-title', 'Mark Attendance')
@section('sidebar-menu')
    <a href="{{ route('teacher.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('teacher.attendance') }}" class="nav-link active"><i class="bi bi-calendar-check"></i> Mark Attendance</a>
    <a href="{{ route('teacher.homeworks') }}" class="nav-link"><i class="bi bi-journal-plus"></i> Homeworks</a>
@endsection
@section('content')
<div class="form-card mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Select Class *</label>
            <select name="class_id" class="form-select">
                <option value="">Choose class...</option>
                @foreach($assignments->unique('class_id') as $a)
                <option value="{{ $a->class_id }}" {{ $selectedClass == $a->class_id ? 'selected' : '' }}>
                    Class {{ $a->class->name ?? 'N/A' }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Date *</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" max="{{ today()->format('Y-m-d') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Load Students</button>
        </div>
    </form>
</div>

@if($students->count() > 0)
<div class="form-card">
    <form method="POST" action="{{ route('teacher.attendance.store') }}">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClass }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <div class="table-card-header mb-3">
            <h6 class="table-card-title">Students ({{ $students->count() }}) - {{ date('d M Y', strtotime($date)) }}</h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-success" onclick="markAll('present')">All Present</button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="markAll('absent')">All Absent</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Roll No</th><th>Student Name</th><th>Present</th><th>Absent</th><th>Late</th></tr></thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->roll_number ?? '-' }}</td>
                        <td class="fw-semibold">{{ $student->user->name }}</td>
                        @foreach(['present','absent','late'] as $status)
                        <td>
                            <div class="form-check">
                                <input class="form-check-input att-radio" type="radio"
                                    name="attendance[{{ $student->id }}]"
                                    value="{{ $status }}"
                                    {{ ($existingAttendance[$student->id]->status ?? 'present') === $status ? 'checked' : '' }}>
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Save Attendance</button>
        </div>
    </form>
</div>
@elseif($selectedClass)
<div class="alert alert-info">No students found for this class.</div>
@endif
@endsection
@push('scripts')
<script>
function markAll(status) {
    document.querySelectorAll('.att-radio[value="' + status + '"]').forEach(r => r.checked = true);
}
</script>
@endpush
