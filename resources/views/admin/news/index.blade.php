@extends('layouts.app')

@section('title', 'Manage News')
@section('header', 'News Management')
@section('subheader', 'Create, edit, and manage platform announcements')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">All News Articles</h2>
            <p class="text-muted mb-0">{{ $news->total() }} total entries</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Write News
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($news->count() > 0)
        <div class="list-group">
            @foreach($news as $item)
            <div class="list-group-item list-group-item-action">
                <div class="d-flex flex-column flex-md-row gap-3">
                    <!-- Content -->
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="mb-1 fw-bold">{{ $item->title }}</h5>
                            <span class="badge 
                                @if($item->is_published) bg-success 
                                @else bg-secondary @endif 
                                ms-2">
                                {{ $item->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>

                        <p class="text-muted small mb-1">
                            {{ Str::limit($item->excerpt, 100) }}
                        </p>

                        <div class="d-flex flex-wrap gap-3 text-muted small mt-2">
                            <span>
                                <i class="fas fa-user me-1"></i>
                                {{ $item->admin->full_name ?? 'Admin' }}
                            </span>
                            <span>
                                <i class="far fa-clock me-1"></i>
                                {{ $item->created_at->format('M d, Y \a\t h:i A') }}
                            </span>
                            @if($item->published_at && $item->is_published)
                                <span>
                                    <i class="fas fa-calendar-check me-1"></i>
                                    Published: {{ $item->published_at->format('M d, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex flex-wrap gap-2 align-self-start">
                        <a href="{{ route('news.show', $item->id) }}" 
                           class="btn btn-sm btn-outline-primary"
                           target="_blank"
                           title="View public page">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.news.edit', $item->id) }}" 
                           class="btn btn-sm btn-outline-warning"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.news.destroy', $item->id) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Delete this news article? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $news->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
            <h5>No news articles yet</h5>
            <p class="text-muted">Start by creating your first announcement.</p>
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">Write News</a>
        </div>
    @endif
</div>
@endsection