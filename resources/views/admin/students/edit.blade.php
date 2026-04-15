@extends('layouts.app')
@section('title', 'Edit Student')
@section('page-title', 'Edit Student')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="form-card">
    <h5 class="fw-bold mb-4">Edit Student: {{ $student->user->name }}</h5>
    <form method="POST" action="{{ route('admin.students.update', $student) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $student->user->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email', $student->user->email) }}" required></div>
            <div class="col-md-4"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $student->user->phone) }}"></div>
            <div class="col-md-4"><label class="form-label">Class *</label><select name="class_id" class="form-select" required>@foreach($classes as $c)<option value="{{ $c->id }}" {{ $student->class_id == $c->id ? 'selected' : '' }}>Class {{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label">Section</label><select name="section_id" class="form-select"><option value="">None</option>@foreach($sections as $s)<option value="{{ $s->id }}" {{ $student->section_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label">DOB *</label><input type="date" name="dob" class="form-control" value="{{ old('dob', $student->dob->format('Y-m-d')) }}" required></div>
            <div class="col-md-4"><label class="form-label">Gender *</label><select name="gender" class="form-select" required><option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Female</option><option value="other" {{ $student->gender == 'other' ? 'selected' : '' }}>Other</option></select></div>
            <div class="col-md-4"><label class="form-label">Roll Number</label><input type="text" name="roll_number" class="form-control" value="{{ old('roll_number', $student->roll_number) }}"></div>
            <div class="col-md-6"><label class="form-label">Father Name *</label><input type="text" name="father_name" class="form-control" value="{{ old('father_name', $student->father_name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Mother Name</label><input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $student->mother_name) }}"></div>
            <div class="col-12"><label class="form-label">Address *</label><textarea name="address" class="form-control" rows="2" required>{{ old('address', $student->address) }}</textarea></div>
            <div class="col-md-4"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $student->city) }}"></div>
            <div class="col-md-4"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ old('state', $student->state) }}"></div>
            <div class="col-md-4"><label class="form-label">PIN Code</label><input type="text" name="pincode" class="form-control" value="{{ old('pincode', $student->pincode) }}"></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Update Student</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection
