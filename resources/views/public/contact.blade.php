@extends('layouts.public')
@section('title', 'Contact Us')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="section-title text-center mb-5">Contact Us</h1>
            <div class="row g-4">
                <div class="col-md-4">
                    @if(!empty($siteSettings['contact_phone']))
                    <div class="card p-4 text-center h-100">
                        <i class="bi bi-telephone" style="font-size:2rem;color:#4f46e5;"></i>
                        <h6 class="fw-bold mt-3">Phone</h6>
                        <p class="text-muted small">{{ $siteSettings['contact_phone'] }}</p>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    @if(!empty($siteSettings['contact_email']))
                    <div class="card p-4 text-center h-100">
                        <i class="bi bi-envelope" style="font-size:2rem;color:#10b981;"></i>
                        <h6 class="fw-bold mt-3">Email</h6>
                        <p class="text-muted small">{{ $siteSettings['contact_email'] }}</p>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    @if(!empty($siteSettings['contact_address']))
                    <div class="card p-4 text-center h-100">
                        <i class="bi bi-geo-alt" style="font-size:2rem;color:#f59e0b;"></i>
                        <h6 class="fw-bold mt-3">Address</h6>
                        <p class="text-muted small">{{ $siteSettings['contact_address'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
