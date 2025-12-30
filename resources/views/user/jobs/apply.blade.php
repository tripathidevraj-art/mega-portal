@extends('layouts.app')

@section('title', 'Apply for ' . $job->job_title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Apply for: {{ $job->job_title }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobs.apply', $job->id) }}" method="POST" enctype="multipart/form-data" id="applyForm">
                        @csrf
<!-- Cover Letter Field -->
<div class="mb-3">
    <label class="form-label">Cover Letter *</label>
    <textarea 
        class="form-control {{ $errors->has('cover_letter') ? 'is-invalid' : '' }}" 
        name="cover_letter" 
        rows="6" 
        required
        minlength="50"
    >{{ old('cover_letter') }}</textarea>
    
    <!-- ✅ Validation Error Message -->
    @if($errors->has('cover_letter'))
        <div class="invalid-feedback">
            {{ $errors->first('cover_letter') }}
        </div>
    @endif
    
    <div class="form-text">Minimum 50 characters</div>
</div>
                        
                        <div class="mb-3">
                            <label class="form-label">Resume (Optional)</label>
                            <input type="file" class="form-control" name="resume" accept=".pdf,.doc,.docx">
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@if(session('success'))
<script>
    $('#applyForm').submit(function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alert(response.message);
            window.close(); 
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'An error occurred');
        }
    });
});

    setTimeout(() => window.close(), 2000);
</script>
@endif