@extends('layouts.app')

@section('title', 'Latest News')
@section('header', 'Announcements & Updates')
@section('subheader', 'Stay informed with the latest platform news')

@section('content')

{{-- Filter & Search Bar --}}
<div class="bg-light rounded-3 p-4 mb-4">
    <form method="GET" id="news-filter-form">
        <input type="hidden" name="view" value="{{ $view }}">

        <div class="row g-3">
            {{-- Search --}}
            <div class="col-12 col-md-5">
                <label for="search" class="form-label visually-hidden">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search news by title or content...">
                </div>
            </div>

            {{-- Sort --}}
            <div class="col-6 col-md-3">
                <label for="sort" class="form-label visually-hidden">Sort</label>
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title A-Z</option>
                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title Z-A</option>
                </select>
            </div>

            {{-- Submit Button (for manual search) --}}
            <div class="col-6 col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
            </div>
        </div>
    </form>
</div>

{{-- View Toggle Buttons --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Latest News ({{ $news->total() }})</h2>
    <div class="btn-group" role="group">
        <a href="{{ route('news.index', array_merge(request()->query(), ['view' => 'grid'])) }}"
           class="btn btn-outline-{{ $view === 'grid' ? 'primary' : 'secondary' }}">
            <i class="fas fa-th-large me-1"></i> Grid
        </a>
        <a href="{{ route('news.index', array_merge(request()->query(), ['view' => 'list'])) }}"
           class="btn btn-outline-{{ $view === 'list' ? 'primary' : 'secondary' }}">
            <i class="fas fa-list me-1"></i> List
        </a>
    </div>
</div>

@if($news->count() > 0)

    @if($view === 'list')
        <!-- LIST VIEW -->
    <div class="space-y-3">
        @foreach($news as $item)
        <div class="border rounded-2 p-3 bg-white shadow-sm hover-shadow d-flex flex-wrap align-items-center gap-3">
            <!-- Image (Square, Small) -->
            @if($item->image)
                <div class="flex-shrink-0" style="width: 60px; height: 60px; overflow: hidden; border-radius: 6px;">
                    <img src="{{ asset('storage/' . $item->image) }}" 
                         class="w-100 h-100 object-fit-cover" 
                         alt="{{ $item->title }}">
                </div>
            @endif

            <!-- Date & CTA -->
<div class="d-flex flex-wrap align-items-center gap-3">
    <!-- Title + Excerpt -->
    <div class="flex-grow-1 min-w-0">
        <h6 class="mb-1 fw-bold text-dark d-inline-block">
            <a href="{{ route('news.show', $item->id) }}" class="text-decoration-none hover-text-primary">
                {{ $item->title }}
            </a>
        </h6>
        <p class="text-muted small mb-0 d-inline-block ms-2">
            • {{ Str::limit($item->excerpt, 80) }}
        </p>
    </div>

    <!-- Stats & CTA -->
    <div class="d-flex align-items-center gap-3 flex-nowrap">
        <div class="text-muted small d-flex align-items-center">
            <i class="fas fa-eye me-1"></i> {{ number_format($item->views) }}
        </div>
        <div class="text-muted small d-flex align-items-center">
            <i class="fas fa-heart me-1 text-danger"></i> {{ number_format($item->likes_count) }}
        </div>
        <a href="{{ route('news.show', $item->id) }}" class="btn btn-sm btn-outline-primary">Read</a>
    </div>
</div>
        </div>
        @endforeach
    </div>

    @else
        <!-- GRID VIEW -->
        <div class="row g-4">
            @foreach($news as $item)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"
                             class="card-img-top"
                             alt="{{ $item->title }}"
                             style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fas fa-newspaper fa-3x text-secondary"></i>
                        </div>
                    @endif
<div class="card-body d-flex flex-column">
    <div class="mb-2">
        <span class="badge bg-info">{{ $item->created_at->format('M d, Y') }}</span>
    </div>
    <h5 class="card-title mb-2">{{ $item->title }}</h5>
    <p class="text-muted small">{{ Str::limit($item->excerpt, 90) }}</p>

    <div class="mt-auto">
        <!-- Stats Row -->
        <div class="d-flex justify-content-between align-items-center mt-2 text-muted small">
            <span>
                <i class="fas fa-eye me-1"></i> {{ number_format($item->views) }}
            </span>
            <span>
                <i class="fas fa-heart me-1 text-danger"></i> {{ number_format($item->likes_count) }}
            </span>
        </div>

        <a href="{{ route('news.show', $item->id) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
            Read Full Article
        </a>
    </div>
</div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $news->appends(['view' => $view, 'search' => request('search'), 'sort' => request('sort')])->links() }}
    </div>

@else
    <div class="text-center py-5">
        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No announcements available</h5>
        <p class="text-muted">
            @if(request('search'))
                No news matched your search.
            @else
                Check back soon for updates!
            @endif
        </p>
        @if(request('search'))
            <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">Clear Search</a>
        @endif
    </div>
@endif

@endsection