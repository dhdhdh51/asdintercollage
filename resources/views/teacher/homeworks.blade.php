@extends('layouts.app')
@section('title', 'Homeworks')
@section('page-title', 'Manage Homeworks')
@section('sidebar-menu')
    <a href="{{ route('teacher.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('teacher.attendance') }}" class="nav-link"><i class="bi bi-calendar-check"></i> Mark Attendance</a>
    <a href="{{ route('teacher.homeworks') }}" class="nav-link active"><i class="bi bi-journal-plus"></i> Homeworks</a>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="form-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2"></i>Upload Homework</h6>
            <form method="POST" action="{{ route('teacher.homeworks.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label small">Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Class *</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">Select...</option>
                        @foreach($classes->unique('class_id') as $a)
                        <option value="{{ $a->class_id }}">Class {{ $a->class->name ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Subject *</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">Select...</option>
                        @foreach($classes->unique('subject_id') as $a)
                        <option value="{{ $a->subject_id }}">{{ $a->subject->name ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Due Date *</label>
                    <input type="date" name="due_date" class="form-control" min="{{ today()->addDay()->format('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Attachment (PDF/Image)</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload me-2"></i>Upload Homework</button>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="table-card">
            <div class="table-card-header"><h6 class="table-card-title">My Uploaded Homeworks</h6></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Title</th><th>Class</th><th>Subject</th><th>Due Date</th><th>File</th></tr></thead>
                    <tbody>
                        @forelse($homeworks as $hw)
                        <tr>
                            <td class="fw-semibold small">{{ $hw->title }}</td>
                            <td>Class {{ $hw->class->name ?? 'N/A' }}</td>
                            <td>{{ $hw->subject->name ?? 'N/A' }}</td>
                            <td class="small {{ $hw->due_date->isPast() ? 'text-danger' : '' }}">{{ $hw->due_date->format('d M Y') }}</td>
                            <td>
                                @if($hw->file_path)<a href="{{ $hw->file_path }}" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-download"></i></a>@else <span class="text-muted small">-</span>@endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No homeworks uploaded</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top">{{ $homeworks->links() }}</div>
        </div>
    </div>
</div>
@endsection
