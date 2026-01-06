@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-1 text-primary">404</h1>
            <h2 class="mb-4">Oops! Page Not Found</h2>
            <p class="lead text-muted mb-4">
                The page you're looking for doesn't exist or has been moved.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="fas fa-home me-1"></i> Go Home
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Go Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection