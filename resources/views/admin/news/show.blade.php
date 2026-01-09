@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if($news->image)
                <img src="{{ asset('storage/'.$news->image) }}" class="w-100 rounded mb-4" alt="{{ $news->title }}">
            @endif
            <h1>{{ $news->title }}</h1>
            <p class="text-muted">{{ $news->created_at->format('F j, Y') }}</p>
            <div class="news-content mt-4">
                {!! $news->content !!} <!-- Renders HTML from TinyMCE -->
            </div>
            <a href="{{ route('news.index') }}" class="btn btn-outline-secondary mt-4">&larr; Back to News</a>
        </div>
    </div>
</div>

<style>
.news-content { font-size: 1.1rem; line-height: 1.7; }
.news-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0; }
</style>
@endsection