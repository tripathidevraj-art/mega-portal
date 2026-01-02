@extends('layouts.app')

@section('title', $offer->product_name)
@section('header', $offer->product_name)
@section('subheader', $offer->category)

@push('styles')
<style>
    .offer-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .price-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }
    .share-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.2s;
    }
    .share-btn:hover {
        transform: translateY(-2px);
    }
    .profile-preview {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <!-- Offer Header -->
                <div class="offer-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="h2 mb-1">{{ $offer->product_name }}</h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-tag me-1"></i> {{ $offer->category }}
                            </p>
                        </div>
                        @if($offer->discount > 0)
                            <span class="badge bg-success fs-5">-{{ $offer->discount }}%</span>
                        @endif
                    </div>

<div class="d-flex gap-3 mt-3">
    @if($offer->price !== null)
        @if($offer->discount > 0)
            <div>
                <small class="text-muted text-decoration-line-through">
                    ${{ number_format($offer->price, 2) }}
                </small>
            </div>
            <div>
                <p class="text-success fw-bold h3 mb-0">
                    ${{ number_format($offer->final_price, 2) }}
                </p>
            </div>
        @else
            <div>
                <p class="text-success fw-bold h3 mb-0">
                    ${{ number_format($offer->price, 2) }}
                </p>
            </div>
        @endif
    @else
        <div>
            <p class="text-muted fst-italic h3 mb-0">Price is Not Given</p>
        </div>
    @endif
</div>
                </div>

                <!-- Product Image -->
                @if($offer->product_image)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $offer->product_image) }}" 
                             alt="{{ $offer->product_name }}" 
                             class="img-fluid rounded border" 
                             style="max-height: 400px; object-fit: contain;">
                    </div>
                @endif

                <!-- Description -->
                <div class="mb-4">
                    <h5 class="mb-3 pb-2 border-bottom">Description</h5>
                    <div class="text-justify">
                        {!! nl2br(e($offer->description)) !!}
                    </div>
                </div>

                <!-- Expiry Warning -->
                @if($offer->expiry_date->lt(now()->addDays(3)))
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <small>This offer expires {{ $offer->expiry_date->diffForHumans() }}!</small>
                    </div>
                @endif

                <!-- Posted By -->
                <div class="pt-3 mt-4 border-top">
                    <h6 class="mb-3">Posted By</h6>
                    <div class="d-flex align-items-center">
                        @if($offer->user->profile_image)
                            <img src="{{ asset('storage/' . $offer->user->profile_image) }}" 
                                 alt="{{ $offer->user->full_name }}" 
                                 class="profile-preview">
                        @else
                            <div class="profile-preview bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-secondary"></i>
                            </div>
                        @endif
                        <div class="ms-3">
                            <p class="mb-0 fw-bold">{{ $offer->user->full_name }}</p>
                            <p class="mb-0 text-muted small">Member since {{ $offer->user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Share Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Share This Offer</h5>
                <div class="d-flex justify-content-center gap-3">
                    <a href="https://wa.me/?text={{ urlencode('Check out this offer: ' . $offer->product_name . ' - ' . route('offers.show', $offer->id)) }}" 
                       target="_blank" 
                       class="share-btn btn btn-success"
                       title="Share on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="mailto:?subject=Offer: {{ urlencode($offer->product_name) }}&body={{ urlencode('Check out this offer: ' . route('offers.show', $offer->id)) }}" 
                       class="share-btn btn btn-primary"
                       title="Share via Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <button onclick="copyOfferLink()" class="share-btn btn btn-info" title="Copy Link">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Offer Details + Quick Actions -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Offer Details</h5>
                
                <!-- Stats -->
                <ul class="list-unstyled mb-4">
                    <li class="mb-2">
                        <i class="fas fa-eye me-2 text-info"></i>
                        <strong>Views:</strong> {{ $offer->views }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar-check me-2 text-warning"></i>
                        <strong>Expires:</strong> {{ $offer->expiry_date->format('F d, Y') }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-clock me-2 text-muted"></i>
                        <strong>Posted:</strong> {{ $offer->created_at->format('M d, Y') }}
                    </li>
                </ul>

                <!-- Divider -->
                <hr class="my-3">

                <!-- Quick Actions -->
                <h6 class="mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    @auth
                        @if(auth()->id() === $offer->user_id)
                            <a href="{{ route('user.offers.my-offers') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i> My Offers
                            </a>
                        @endif
                    @endauth
                    <a href="{{ route('user.offers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Post an Offer
                    </a>
                    <a href="{{ route('offers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-tag me-2"></i> Browse Offers
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function copyOfferLink() {
    const link = window.location.href;
    navigator.clipboard.writeText(link).then(() => {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">Link copied to clipboard!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 2000 });
        bsToast.show();
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    });
}
</script>
@endpush