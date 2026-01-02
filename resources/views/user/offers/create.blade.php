@extends('layouts.app')

@section('title', 'Post New Offer')
@section('header', 'Post a New Offer')
@section('subheader', 'Create a product offer to attract buyers')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form id="offerForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="product_name" class="form-control" placeholder="e.g. Wireless Bluetooth Headphones" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" id="categorySelect" required>
                                <option value="">Select Category</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Fashion">Fashion</option>
                                <option value="Health & Beauty">Health & Beauty</option>
                                <option value="Home & Kitchen">Home & Kitchen</option>
                                <option value="Sports & Outdoors">Sports & Outdoors</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="otherCategoryField" class="mt-2" style="display: none;">
                                <input type="text" 
                                    name="other_category" 
                                    class="form-control" 
                                    placeholder="Enter your custom category..." 
                                    maxlength="100">
                                <div class="form-text">e.g., Automotive, Books, Services, etc.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price ($)</label>
                            <input type="number" name="price" class="form-control" min="0" step="0.01" placeholder="e.g. 99.99">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" name="discount" class="form-control" min="0" max="100" placeholder="e.g. 15">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" name="expiry_date" class="form-control" min="{{ now()->addDay()->toDateString() }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Promo Image</label>
                        <input type="file" name="product_image" class="form-control" accept="image/*">
                        <div class="form-text">Max file size: 2MB. Supported: JPG, PNG, GIF</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Describe your product features, condition, and highlights..." required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                            Post Offer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('user.offers.my-offers') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to My Offers
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#offerForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = $('#submitBtn');
        const originalText = btn.html();
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> Posting...');
        
        $.ajax({
            url: '{{ route("user.offers.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                let msg = 'An error occurred';
                if (xhr.responseJSON?.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                alert(msg);
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
        $('#categorySelect').on('change', function() {
        if (this.value === 'Other') {
            $('#otherCategoryField').show();
            $('input[name="other_category"]').prop('required', true);
        } else {
            $('#otherCategoryField').hide();
            $('input[name="other_category"]').prop('required', false).val('');
        }
    });
    // Set min expiry date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().split('T')[0];
    $('input[name="expiry_date"]').attr('min', minDate);
});
</script>
@endpush