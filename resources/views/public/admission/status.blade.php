@extends('layouts.public')
@section('title', 'Track Admission Status')
@section('meta_description', 'Track your admission application status using your Application ID.')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <h1 class="fw-bold section-title">Track Application Status</h1>
                <p class="text-muted">Enter your Application ID to check the status</p>
            </div>

            <div class="card p-4 mb-4">
                <form method="GET" action="{{ route('admission.status') }}">
                    <div class="mb-3">
                        <label class="form-label">Application ID *</label>
                        <input type="text" name="id" class="form-control form-control-lg"
                            placeholder="e.g. APP20240001"
                            value="{{ request('id') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-search me-2"></i>Check Status
                    </button>
                </form>
            </div>

            @if($error)
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>{{ $error }}</div>
            @endif

            @if($admission)
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold mb-0">{{ $admission->student_name }}</h5>
                        <code class="d-block">{{ $admission->application_id }}</code>
                    </div>
                    <span class="badge bg-{{ $admission->status_badge }} fs-6 px-3 py-2">
                        {{ ucfirst($admission->status) }}
                    </span>
                </div>

                <!-- Progress Tracker -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        @foreach(['Submitted', 'Under Review', $admission->status === 'rejected' ? 'Rejected' : 'Approved'] as $i => $step)
                        <div class="text-center flex-fill">
                            <div class="mx-auto mb-1 rounded-circle d-flex align-items-center justify-content-center"
                                style="width:36px;height:36px;background:{{ $i == 0 || ($i == 1 && in_array($admission->status, ['approved','rejected'])) || ($i == 2 && in_array($admission->status, ['approved','rejected'])) ? '#4f46e5' : '#e2e8f0' }};color:{{ $i == 0 || ($i == 1 && in_array($admission->status, ['approved','rejected'])) || ($i == 2 && in_array($admission->status, ['approved','rejected'])) ? 'white' : '#94a3b8' }};font-size:1rem;">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="small fw-semibold">{{ $step }}</div>
                        </div>
                        @if(!$loop->last)<hr class="flex-fill" style="margin-top:-10px;">@endif
                        @endforeach
                    </div>
                </div>

                <table class="table table-sm">
                    <tr><td class="text-muted">Father's Name</td><td>{{ $admission->father_name }}</td></tr>
                    <tr><td class="text-muted">Class Applied</td><td>Class {{ $admission->class->name ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Phone</td><td>{{ $admission->phone }}</td></tr>
                    <tr><td class="text-muted">Applied On</td><td>{{ $admission->created_at->format('d M Y') }}</td></tr>
                    @if($admission->remarks)
                    <tr><td class="text-muted">Remarks</td><td>{{ $admission->remarks }}</td></tr>
                    @endif
                </table>

                @if($admission->status === 'approved')
                <div class="alert alert-success mt-3 mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Congratulations!</strong> Your admission has been approved. Please visit the school office with required documents.
                </div>
                @elseif($admission->status === 'rejected')
                <div class="alert alert-danger mt-3 mb-0">
                    <i class="bi bi-x-circle me-2"></i>
                    Your application has been rejected. Please contact the school office for more information.
                </div>
                @else
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-hourglass-split me-2"></i>
                    Your application is under review. You'll receive an email notification when it's processed.
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
