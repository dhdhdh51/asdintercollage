@extends('layouts.app')
@section('title', 'Add Student')
@section('page-title', 'Add New Student')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="form-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-person-plus me-2"></i>Student Enrollment Form</h5>
    <form method="POST" action="{{ route('admin.students.store') }}">
        @csrf
        <div class="row g-3">
            <!-- Personal Info -->
            <div class="col-12"><h6 class="text-muted fw-semibold border-bottom pb-2">Personal Information</h6></div>
            <div class="col-md-6">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Date of Birth *</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Gender *</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="">Select...</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}">{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Religion</label>
                <input type="text" name="religion" class="form-control" value="{{ old('religion') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Caste</label>
                <input type="text" name="caste" class="form-control" value="{{ old('caste') }}">
            </div>

            <!-- Academic Info -->
            <div class="col-12"><h6 class="text-muted fw-semibold border-bottom pb-2 mt-2">Academic Details</h6></div>
            <div class="col-md-4">
                <label class="form-label">Class *</label>
                <select name="class_id" class="form-select" required>
                    <option value="">Select Class...</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            Class {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Section</label>
                <select name="section_id" class="form-select">
                    <option value="">Select Section...</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }} (Class {{ $section->class->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Admission Year *</label>
                <input type="number" name="admission_year" class="form-control"
                    value="{{ old('admission_year', date('Y')) }}" min="2000" max="{{ date('Y') + 1 }}" required>
            </div>

            <!-- Family Info -->
            <div class="col-12"><h6 class="text-muted fw-semibold border-bottom pb-2 mt-2">Family Information</h6></div>
            <div class="col-md-6">
                <label class="form-label">Father Name *</label>
                <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Mother Name</label>
                <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Father Phone</label>
                <input type="text" name="father_phone" class="form-control" value="{{ old('father_phone') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Mother Phone</label>
                <input type="text" name="mother_phone" class="form-control" value="{{ old('mother_phone') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Father Occupation</label>
                <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation') }}">
            </div>

            <!-- Address -->
            <div class="col-12"><h6 class="text-muted fw-semibold border-bottom pb-2 mt-2">Address</h6></div>
            <div class="col-12">
                <label class="form-label">Address *</label>
                <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form-control" value="{{ old('state') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">PIN Code</label>
                <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" maxlength="6">
            </div>

            <!-- Login Credentials -->
            <div class="col-12"><h6 class="text-muted fw-semibold border-bottom pb-2 mt-2">Login Credentials</h6></div>
            <div class="col-md-6">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" required minlength="8"
                    placeholder="Min 8 characters">
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-person-check me-2"></i>Enroll Student
            </button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection
