@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $news->title }}</h1>
        <div>
            <a href="{{ route('news.show', $news->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">Public View</a>
            <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-sm btn-warning">Edit</a>
        </div>
    </div>

    <div class="alert alert-{{ $news->is_published ? 'success' : 'warning' }} mb-4">
        <strong>Status:</strong> {{ $news->is_published ? 'Published' : 'Draft' }}
        @if($news->published_at)
            | Scheduled: {{ $news->published_at->format('M d, Y H:i') }}
        @endif
    </div>

    @if($news->image)
        <img src="{{ asset('storage/'.$news->image) }}" class="w-100 rounded mb-4" alt="{{ $news->title }}">
    @endif

    <div class="news-content mt-4">
        {!! $news->content !!}
    </div>

    <!-- Admin Metadata -->
    <div class="mt-5 p-3 bg-light rounded">
        <h5>Admin Info</h5>
        <ul class="list-unstyled mb-0">
            <li><strong>Author:</strong> {{ $news->admin->full_name ?? '—' }}</li>
            <li><strong>Created:</strong> {{ $news->created_at->format('M d, Y H:i') }}</li>
            <li><strong>Views:</strong> {{ $news->views }}</li>
            <li><strong>Likes:</strong> {{ $news->likes_count }}</li>
        </ul>
    </div>

    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary mt-4">&larr; Back to News</a>
</div>

<style>
.news-content { font-size: 1.1rem; line-height: 1.7; }
.news-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0; }
</style>
@endsection