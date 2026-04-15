@extends('layouts.app')
@section('title', 'Send Notification')
@section('page-title', 'Send Notification')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="form-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-bell me-2"></i>Send Notification</h5>
    <form method="POST" action="{{ route('admin.notifications.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Type *</label>
                <select name="type" class="form-select" required>
                    <option value="info">Info</option>
                    <option value="success">Success</option>
                    <option value="warning">Warning</option>
                    <option value="danger">Alert</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Target Audience</label>
                <select name="target_role" class="form-select">
                    <option value="">Everyone</option>
                    <option value="student">Students</option>
                    <option value="teacher">Teachers</option>
                    <option value="parent">Parents</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" name="send_email" id="sendEmail" value="1">
                    <label class="form-check-label" for="sendEmail">Send via Email</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Message *</label>
                <textarea name="message" class="form-control" rows="5" required></textarea>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-2"></i>Send Notification</button>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection
