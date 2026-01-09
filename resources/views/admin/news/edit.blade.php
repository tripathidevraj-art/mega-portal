@extends('layouts.app')

@section('title', 'Edit News')

@section('content')
<div class="container py-4">
    <h2>Edit Announcement</h2>

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Excerpt</label>
            <textarea name="excerpt" class="form-control" rows="2" maxlength="500" required>{{ old('excerpt', $news->excerpt) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            @if($news->image)
                <img src="{{ asset('storage/'.$news->image) }}" alt="Banner" style="max-height: 150px; margin-bottom: 10px;">
            @else
                <em>No image</em>
            @endif
            <div class="mt-2">
                <label>Replace Banner Image (optional)</label>
                <input type="file" name="image" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" id="news-content" class="form-control">{{ old('content', $news->content) }}</textarea>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_published" id="is_published" class="form-check-input" value="1"
                {{ (old('is_published', $news->is_published)) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Publish Immediately</label>
        </div>

        <button type="submit" class="btn btn-primary">Update News</button>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/yfoiijgdusmclts8f8wdx3ara42nqu6a5gik5xne9nzcpln2/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#news-content',
            plugins: 'link image code lists table',
            toolbar: 'undo redo | bold italic | link image | alignleft aligncenter alignright | numlist bullist | code',
            menubar: false,
            height: 400,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
                document.querySelector('form').addEventListener('submit', function() {
                    editor.save();
                });
            }
        });
    </script>
@endpush
@endsection