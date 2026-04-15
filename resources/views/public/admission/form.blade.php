@extends('layouts.public')
@section('title', 'Online Admission Form')
@section('meta_description', 'Apply for admission online. Fill the form and track your application status.')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h1 class="fw-bold section-title">Online Admission Form</h1>
                <p class="text-muted">Academic Year {{ date('Y') }}-{{ date('Y')+1 }}</p>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="card p-4">
                <form method="POST" action="{{ route('admission.submit') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12"><h6 class="fw-semibold border-bottom pb-2 text-muted">Student Information</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Student Full Name *</label>
                            <input type="text" name="student_name" class="form-control" value="{{ old('student_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Class Applying For *</label>
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
                            <label class="form-label">Phone (Parent/Guardian) *</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>

                        <div class="col-12 mt-2"><h6 class="fw-semibold border-bottom pb-2 text-muted">Parent Information</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Father's Name *</label>
                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email (for notifications)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>

                        <div class="col-12 mt-2"><h6 class="fw-semibold border-bottom pb-2 text-muted">Address</h6></div>
                        <div class="col-12">
                            <label class="form-label">Full Address *</label>
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

                        <div class="col-12 mt-2"><h6 class="fw-semibold border-bottom pb-2 text-muted">Previous School (if any)</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Previous School Name</label>
                            <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Previous Class</label>
                            <input type="text" name="previous_class" class="form-control" value="{{ old('previous_class') }}" placeholder="e.g. Class 5">
                        </div>

                        <div class="col-12 mt-2"><h6 class="fw-semibold border-bottom pb-2 text-muted">Documents</h6></div>
                        <div class="col-12">
                            <label class="form-label">Upload Document (Birth Certificate / Transfer Certificate)</label>
                            <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Accepted formats: PDF, JPG, PNG. Max size: 5MB</div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="agree" required>
                                <label class="form-check-label small" for="agree">
                                    I confirm that all the information provided is accurate and true to the best of my knowledge.
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-send me-2"></i>Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle me-2"></i>
                After submission, you'll receive an <strong>Application ID</strong>. Use it to track your application status on our
                <a href="{{ route('admission.status') }}">status tracker page</a>.
            </div>
        </div>
    </div>
</div>
@endsection
