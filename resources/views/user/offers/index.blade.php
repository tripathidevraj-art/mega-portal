@extends('layouts.app')

@section('title', 'All Active Offers')
@section('header', 'Browse Offers')
@section('subheader', 'Discover great deals from verified sellers')

@section('content')

{{-- Filter & Search Bar --}}
<div class="bg-light rounded-3 p-4 mb-4">
    <form method="GET" id="offer-filter-form">
        {{-- Keep view mode --}}
        <input type="hidden" name="view" value="{{ $view }}">

        <div class="row g-3">
            {{-- Search --}}
            <div class="col-12 col-md-4">
                <label for="search" class="form-label visually-hidden">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="form-control" 
                           placeholder="Search offers, product, category...">
                </div>
            </div>

            {{-- Category --}}
            <div class="col-6 col-md-2">
                <label for="category" class="form-label visually-hidden">Category</label>
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort --}}
            <div class="col-6 col-md-2">
                <label for="sort" class="form-label visually-hidden">Sort</label>
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="expiry_soon" {{ request('sort') == 'expiry_soon' ? 'selected' : '' }}>Expiry Soon</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low</option>
                    <option value="discount_high" {{ request('sort') == 'discount_high' ? 'selected' : '' }}>Discount: High</option>
                </select>
            </div>
        </div>
    </form>
</div>

{{-- View Toggle Buttons --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Active Offers ({{ $offers->total() }})</h2>
    <div class="btn-group" role="group">
        <a href="{{ route('offers.index', array_merge(request()->query(), ['view' => 'grid'])) }}" 
           class="btn btn-outline-{{ $view === 'grid' ? 'primary' : 'secondary' }}">
            <i class="fas fa-th-large me-1"></i> Grid
        </a>
        <a href="{{ route('offers.index', array_merge(request()->query(), ['view' => 'list'])) }}" 
           class="btn btn-outline-{{ $view === 'list' ? 'primary' : 'secondary' }}">
            <i class="fas fa-list me-1"></i> List
        </a>
    </div>
</div>

@if($offers->count() > 0)

    @if($view === 'list')
        <!-- LIST VIEW -->
<div class="space-y-4">
    @foreach($offers as $offer)
        <div class="border rounded-3 p-4 bg-white shadow-sm hover-shadow transition-all">
            <div class="d-flex flex-column flex-md-row gap-4">
                <!-- Product Image -->
                <div class="flex-shrink-0" style="width: 110px; height: 110px;">
                    @if($offer->product_image)
                        <img src="{{ asset('storage/' . $offer->product_image) }}" 
                             alt="{{ $offer->product_name }}"
                             class="img-fluid rounded"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded h-100">
                            <i class="fas fa-box-open text-secondary fa-2x"></i>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-grow-1 d-flex flex-column">
                    <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                        <h5 class="mb-0">
                            <a href="{{ route('offers.show', $offer->id) }}" 
                               class="text-decoration-none text-dark fw-bold hover-text-primary">
                                {{ $offer->product_name }}
                            </a>
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-primary">{{ $offer->category }}</span>
                            @if($offer->discount > 0)
                                <span class="badge bg-success">-{{ number_format($offer->discount, 0) }}%</span>
                            @endif
                        </div>
                    </div>

                    <p class="text-muted small mb-3">{{ Str::limit($offer->description, 140) }}</p>

                    <!-- Stats Row -->
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-auto pt-2 text-muted small">
                        <span>
                            <i class="fas fa-eye fa-sm me-1"></i> {{ $offer->views }} views
                        </span>
                        <span>
                            <i class="fas fa-calendar-alt fa-sm me-1"></i> Expires: {{ $offer->expiry_date->format('M d, Y') }}
                        </span>
                    </div>
                </div>

                <!-- Price & CTA -->
                <div class="d-flex flex-column align-items-end justify-content-between" style="min-width: 140px;">
                    @if($offer->price !== null)
                        @if($offer->discount > 0)
                            <div class="text-end">
                                <p class="mb-1">
                                    <small class="text-muted"><del>${{ number_format($offer->price, 2) }}</del></small>
                                </p>
                                <p class="h5 text-success fw-bold mb-0">${{ number_format($offer->final_price, 2) }}</p>
                            </div>
                        @else
                            <p class="h5 text-success fw-bold mb-0">${{ number_format($offer->price, 2) }}</p>
                        @endif
                    @else
                        <p class="text-muted fst-italic text-end">Price Not Given</p>
                    @endif

                    <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-sm btn-primary mt-2">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

    @else
        <!-- GRID VIEW (original layout preserved) -->
        <div class="row">
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
                                <p class="text-muted fst-italic">Price Not Given</p>
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
        </div>
    @endif

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $offers->appends(['view' => $view])->links() }}
    </div>

@else
    <div class="col-12 text-center py-5">
        <i class="fas fa-tag fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No active offers available</h5>
        <p class="text-muted">Check back soon for new deals!</p>
    </div>
@endif

@endsection