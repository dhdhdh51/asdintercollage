@extends('layouts.app')
@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="form-card mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small">Class</label>
            <select name="class_id" class="form-select" required>
                <option value="">Select Class...</option>
                @foreach($classes as $class)<option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>Class {{ $class->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Month</label>
            <input type="month" name="month" class="form-control" value="{{ request('month', date('Y-m')) }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </div>
    </form>
</div>
@if(!empty($data))
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Student</th><th>Total Days</th><th>Present</th><th>Absent</th><th>%</th></tr></thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td class="fw-semibold">{{ $row['student']->user->name }}</td>
                    <td>{{ $row['total'] }}</td>
                    <td class="text-success">{{ $row['present'] }}</td>
                    <td class="text-danger">{{ $row['absent'] }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-fill" style="height:6px;">
                                <div class="progress-bar {{ $row['percent'] >= 75 ? 'bg-success' : 'bg-danger' }}" style="width:{{ $row['percent'] }}%"></div>
                            </div>
                            <span class="small {{ $row['percent'] >= 75 ? 'text-success' : 'text-danger' }}">{{ $row['percent'] }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
