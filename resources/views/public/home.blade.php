@extends('layouts.public')
@section('title', ($siteSettings['meta_title'] ?? $siteSettings['site_name'] ?? 'School ERP') . ' - Best School Management System')
@section('meta_description', $siteSettings['meta_description'] ?? 'Complete School ERP System with admission, fees, attendance, and more.')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Empowering Education with Smart Technology
                </h1>
                <p class="lead mb-4 opacity-90">
                    {{ $siteSettings['site_tagline'] ?? 'Complete School Management System - Streamline admissions, fees, attendance, and more.' }}
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('admission.form') }}" class="btn btn-light btn-lg px-4 fw-semibold">
                        <i class="bi bi-person-plus me-2"></i>Apply for Admission
                    </a>
                    <a href="{{ route('admission.status') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-search me-2"></i>Track Application
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center">
                <div style="font-size:12rem;opacity:0.2;">🎓</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Why Choose Our School ERP?</h2>
            <p class="text-muted">Everything you need to manage a modern school efficiently</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon' => 'mortarboard-fill', 'title' => 'Online Admission', 'desc' => 'Easy online admission process with document upload and real-time status tracking.', 'color' => '#4f46e5'],
                ['icon' => 'cash-coin', 'title' => 'Fee Management', 'desc' => 'Collect fees online via PayU gateway. Auto-generate invoices and payment receipts.', 'color' => '#10b981'],
                ['icon' => 'calendar-check-fill', 'title' => 'Attendance Tracking', 'desc' => 'Mark and monitor student attendance with detailed reports and percentage tracking.', 'color' => '#06b6d4'],
                ['icon' => 'people-fill', 'title' => 'Multi-Role Access', 'desc' => 'Separate portals for Admin, Teacher, Student, and Parent with role-based access.', 'color' => '#f59e0b'],
                ['icon' => 'bell-fill', 'title' => 'Email Notifications', 'desc' => 'Automated email alerts for admission updates, fee payments, and announcements.', 'color' => '#ef4444'],
                ['icon' => 'graph-up', 'title' => 'Analytics Dashboard', 'desc' => 'Real-time analytics and reports for informed decision making.', 'color' => '#8b5cf6'],
            ] as $feature)
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <div class="feature-icon" style="background:{{ $feature['color'] }}1a;color:{{ $feature['color'] }};">
                        <i class="bi bi-{{ $feature['icon'] }}"></i>
                    </div>
                    <h5 class="fw-bold">{{ $feature['title'] }}</h5>
                    <p class="text-muted small mb-0">{{ $feature['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Quick Links Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <a href="{{ route('admission.form') }}" class="text-decoration-none">
                    <div class="card p-4 hover-card">
                        <i class="bi bi-file-earmark-person" style="font-size:3rem;color:#4f46e5;"></i>
                        <h5 class="mt-3 fw-bold text-dark">Apply Now</h5>
                        <p class="text-muted small">Fill admission form online</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admission.status') }}" class="text-decoration-none">
                    <div class="card p-4">
                        <i class="bi bi-search" style="font-size:3rem;color:#10b981;"></i>
                        <h5 class="mt-3 fw-bold text-dark">Track Status</h5>
                        <p class="text-muted small">Check application status</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    <div class="card p-4">
                        <i class="bi bi-mortarboard" style="font-size:3rem;color:#06b6d4;"></i>
                        <h5 class="mt-3 fw-bold text-dark">Student Portal</h5>
                        <p class="text-muted small">Login to student dashboard</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    <div class="card p-4">
                        <i class="bi bi-people" style="font-size:3rem;color:#f59e0b;"></i>
                        <h5 class="mt-3 fw-bold text-dark">Parent Portal</h5>
                        <p class="text-muted small">Monitor your child's progress</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
@if($posts->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Latest Announcements</h2>
            <a href="{{ route('blog') }}" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row g-4">
            @foreach($posts as $post)
            <div class="col-md-4">
                <div class="card h-100">
                    @if($post->image)
                    <img src="{{ $post->image }}" class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $post->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">{{ $post->title }}</a>
                        </h5>
                        <p class="card-text text-muted small">{{ $post->excerpt }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $post->published_at?->format('d M Y') }}</small>
                            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
