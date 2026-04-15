@extends('layouts.app')
@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">Notifications</h4>
    <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Send Notification</a>
</div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Title</th><th>Target</th><th>Type</th><th>Email Sent</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($notifications as $notif)
                <tr>
                    <td class="fw-semibold">{{ $notif->title }}</td>
                    <td><span class="badge bg-secondary">{{ $notif->target_role ?? 'All' }}</span></td>
                    <td><span class="badge bg-{{ $notif->type }}">{{ ucfirst($notif->type) }}</span></td>
                    <td>{{ $notif->email_sent ? '<i class="bi bi-check-circle text-success"></i> Yes' : '<span class="text-muted">No</span>' }}</td>
                    <td class="text-muted small">{{ $notif->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No notifications</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">{{ $notifications->links() }}</div>
</div>
@endsection
