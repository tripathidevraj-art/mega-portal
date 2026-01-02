@extends('layouts.app')

@section('title', 'All Active Offers')
@section('header', 'Browse Offers')
@section('subheader', 'Discover great deals from verified sellers')

@section('content')
<div class="row">
    @if($offers->isEmpty())
        <div class="col-12 text-center py-5">
            <i class="fas fa-tag fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No active offers available</h5>
            <p class="text-muted">Check back soon for new deals!</p>
        </div>
    @else
        @foreach($offers as $offer)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                @if($offer->product_image)
                    <img src="{{ asset('storage/' . $offer->product_image) }}" 
                         class="card-img-top" 
                         alt="{{ $offer->product_name }}"
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-box-open fa-3x text-secondary"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge bg-primary">{{ $offer->category }}</span>
                        @if($offer->discount > 0)
                            <span class="badge bg-success ms-1">-{{ $offer->discount }}%</span>
                        @endif
                    </div>
                    <h5 class="card-title mb-2">{{ $offer->product_name }}</h5>
                    <p class="text-muted small">{{ Str::limit($offer->description, 80) }}</p>
                    
                    <div class="mt-auto">
                        @if($offer->price !== null)
                            @if($offer->discount > 0)
                                <p class="mb-1">
                                    <small class="text-muted"><del>${{ number_format($offer->price, 2) }}</del></small>
                                </p>
                                <p class="h5 text-success fw-bold mb-0">${{ number_format($offer->final_price, 2) }}</p>
                            @else
                                <p class="h5 text-success fw-bold mb-0">${{ number_format($offer->price, 2) }}</p>
                            @endif
                        @else
                            <p class="text-muted fst-italic">Price is Not Given</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i> {{ $offer->views }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i> {{ $offer->expiry_date->format('M d') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-outline-primary w-100">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $offers->links() }}
</div>
@endsection