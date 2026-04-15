@extends('layouts.app')
@section('title', 'Add Teacher')
@section('page-title', 'Add New Teacher')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="form-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-person-plus me-2"></i>Teacher Registration Form</h5>
    <form method="POST" action="{{ route('admin.teachers.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-12"><h6 class="text-muted fw-semibold border-bottom pb-2">Personal Information</h6></div>
            <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
            <div class="col-md-4"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" name="dob" class="form-control" value="{{ old('dob') }}"></div>
            <div class="col-md-4"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">Select...</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
            <div class="col-12"><h6 class="text-muted fw-semibold border-bottom pb-2 mt-2">Professional Details</h6></div>
            <div class="col-md-6"><label class="form-label">Qualification</label><input type="text" name="qualification" class="form-control" value="{{ old('qualification') }}" placeholder="e.g. B.Ed, M.Sc, M.Tech"></div>
            <div class="col-md-6"><label class="form-label">Specialization</label><input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}" placeholder="e.g. Mathematics, Science"></div>
            <div class="col-md-4"><label class="form-label">Joining Date</label><input type="date" name="joining_date" class="form-control" value="{{ old('joining_date') }}"></div>
            <div class="col-md-4"><label class="form-label">Monthly Salary (₹)</label><input type="number" name="salary" class="form-control" value="{{ old('salary', 0) }}" min="0"></div>
            <div class="col-md-4"><label class="form-label">Emergency Contact</label><input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}"></div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
            <div class="col-12"><h6 class="text-muted fw-semibold border-bottom pb-2 mt-2">Login Credentials</h6></div>
            <div class="col-md-6"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required minlength="8"></div>
            <div class="col-md-6"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-person-check me-2"></i>Add Teacher</button>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection
