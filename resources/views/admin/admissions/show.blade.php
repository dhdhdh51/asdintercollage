@extends('layouts.app')
@section('title', 'Admission Details')
@section('page-title', 'Admission Details')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold mb-1">{{ $admission->student_name }}</h5>
                    <code>{{ $admission->application_id }}</code>
                </div>
                <span class="badge bg-{{ $admission->status_badge }} fs-6">{{ ucfirst($admission->status) }}</span>
            </div>

            <div class="row g-3">
                <div class="col-md-6"><div class="small text-muted">Father Name</div><div class="fw-semibold">{{ $admission->father_name }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Mother Name</div><div>{{ $admission->mother_name ?? 'N/A' }}</div></div>
                <div class="col-md-4"><div class="small text-muted">Date of Birth</div><div>{{ $admission->dob->format('d M Y') }}</div></div>
                <div class="col-md-4"><div class="small text-muted">Gender</div><div>{{ ucfirst($admission->gender) }}</div></div>
                <div class="col-md-4"><div class="small text-muted">Class Applied</div><div class="fw-semibold">Class {{ $admission->class->name ?? 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Phone</div><div>{{ $admission->phone }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Email</div><div>{{ $admission->email ?? 'N/A' }}</div></div>
                <div class="col-12"><div class="small text-muted">Address</div><div>{{ $admission->address }}, {{ $admission->city }}, {{ $admission->state }} {{ $admission->pincode }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Previous School</div><div>{{ $admission->previous_school ?? 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Previous Class</div><div>{{ $admission->previous_class ?? 'N/A' }}</div></div>
                @if($admission->document_path)
                <div class="col-12">
                    <div class="small text-muted mb-1">Uploaded Document</div>
                    <a href="{{ $admission->document_path }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark me-1"></i>View Document
                    </a>
                </div>
                @endif
                @if($admission->remarks)
                <div class="col-12">
                    <div class="small text-muted">Admin Remarks</div>
                    <div class="p-2 bg-light rounded">{{ $admission->remarks }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($admission->status === 'pending')
        <div class="form-card mb-3">
            <h6 class="fw-bold mb-3 text-success"><i class="bi bi-check-circle me-2"></i>Approve Application</h6>
            <form method="POST" action="{{ route('admin.admissions.approve', $admission) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small">Remarks (optional)</label>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Add note for applicant..."></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this admission?')">
                    <i class="bi bi-check-lg me-2"></i>Approve Admission
                </button>
            </form>
        </div>

        <div class="form-card">
            <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-x-circle me-2"></i>Reject Application</h6>
            <form method="POST" action="{{ route('admin.admissions.reject', $admission) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small">Reason for Rejection *</label>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="State reason for rejection..." required></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this admission?')">
                    <i class="bi bi-x-lg me-2"></i>Reject Application
                </button>
            </form>
        </div>
        @else
        <div class="form-card">
            <h6 class="fw-bold mb-3">Review Details</h6>
            <table class="table table-sm mb-0">
                <tr><td class="text-muted small">Status</td><td><span class="badge bg-{{ $admission->status_badge }}">{{ ucfirst($admission->status) }}</span></td></tr>
                <tr><td class="text-muted small">Reviewed By</td><td>{{ $admission->reviewer->name ?? 'N/A' }}</td></tr>
                <tr><td class="text-muted small">Reviewed At</td><td>{{ $admission->reviewed_at ? $admission->reviewed_at->format('d M Y H:i') : 'N/A' }}</td></tr>
            </table>
        </div>
        @endif

        <div class="form-card mt-3">
            <h6 class="fw-bold mb-2">Application Info</h6>
            <table class="table table-sm mb-0">
                <tr><td class="text-muted small">Applied On</td><td>{{ $admission->created_at->format('d M Y') }}</td></tr>
                <tr><td class="text-muted small">Academic Year</td><td>{{ $admission->academic_year }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection
