@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Create New Announcement</h2>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Excerpt (for homepage)</label>
            <textarea name="excerpt" class="form-control" rows="2" maxlength="500" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Banner Image (optional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Content (Full Article)</label>
            <textarea name="content" id="news-content" class="form-control"></textarea>
        </div>

        <div class="mb-3 form-check">
            <div class="form-check mb-3">
    <input 
        type="checkbox" 
        name="is_published" 
        id="is_published" 
        class="form-check-input" 
        value="1" 
        checked
    >
    <label class="form-check-label" for="is_published">
        Publish Immediately
    </label>
</div>
           
        </div>

        <button type="submit" class="btn btn-primary">Publish News</button>
    </form>
</div>

@push('scripts')
    <!-- TinyMCE CDN -->
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
            // Also save on form submit
            document.querySelector('form').addEventListener('submit', function() {
                editor.save();
            });
        }
    });
    </script>
@endpush
@endsection