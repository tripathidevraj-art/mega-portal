@extends('layouts.app')

@section('title', 'My Offers')
@section('header', 'My Product Offers')
@section('subheader', 'Manage your posted offers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>My Product Offers</h5>
            <a href="{{ route('user.offers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Post New Offer
            </a>
        </div>

        @if($offers->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-tag fa-3x text-muted mb-3"></i>
                <p class="text-muted">You haven't posted any offers yet.</p>
                <a href="{{ route('user.offers.create') }}" class="btn btn-outline-primary">Create Your First Offer</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Expiry</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $offer)
                        <tr>
                            <td>
                                <a href="{{ route('offers.show', $offer->id) }}" class="text-decoration-none">
                                    <strong>{{ $offer->product_name }}</strong>
                                </a>
                                <br>
                                <small class="text-muted">{{ Str::limit($offer->description, 30) }}</small>
                            </td>
                            <td>{{ $offer->category }}</td>
                            <td>${{ number_format($offer->price, 2) }}</td>
                            <td>
                                @if($offer->discount > 0)
                                    <span class="badge bg-success">-{{ $offer->discount }}%</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($offer->expiry_date->lt(now()->addDays(3)))
                                    <span class="text-danger fw-bold">{{ $offer->expiry_date->format('M d, Y') }}</span>
                                @else
                                    {{ $offer->expiry_date->format('M d, Y') }}
                                @endif
                            </td>
                            <td>{{ $offer->views }}</td>
                            <td>
                                @if($offer->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($offer->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($offer->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">Expired</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-sm btn-info" target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('user.offers.share', $offer->id) }}" 
                                   class="btn btn-sm btn-success mt-1"
                                   onclick="shareOffer({{ $offer->id }}); return false;">
                                    <i class="fas fa-share-alt"></i> Share
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $offers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function shareOffer(offerId) {
    fetch(`/user/offers/${offerId}/share`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.open(data.links.whatsapp, '_blank');
            }
        });
}
</script>
@endpush