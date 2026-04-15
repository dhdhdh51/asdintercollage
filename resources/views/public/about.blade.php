@extends('layouts.public')
@section('title', 'About Us')
@section('content')
<div class="container py-5">
    <div class="text-center">
        <h1 class="section-title">About {{ $siteSettings['site_name'] ?? 'Our School' }}</h1>
        <p class="text-muted lead">{{ $siteSettings['site_tagline'] ?? 'Empowering Education Through Technology' }}</p>
    </div>
</div>
@endsection
