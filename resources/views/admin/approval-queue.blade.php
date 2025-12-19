@extends('layouts.app')

@section('title', 'Approval Queue')

@section('header', 'Approval Queue')
@section('subheader', 'Pending Jobs & Offers')

@section('content')
<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="approvalTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobs" type="button">
            Jobs <span class="badge bg-warning">{{ $pendingJobs->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="offers-tab" data-bs-toggle="tab" data-bs-target="#offers" type="button">
            Offers <span class="badge bg-warning">{{ $pendingOffers->count() }}</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="approvalTabsContent">
    <!-- Jobs Tab -->
    <div class="tab-pane fade show active" id="jobs" role="tabpanel">
        @if($pendingJobs->isEmpty())
            <div class="alert alert-info">
                No pending jobs for approval.
            </div>
        @else
            @foreach($pendingJobs as $job)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $job->job_title }}</h5>
                            <small class="text-muted">Posted by: {{ $job->user->name }}</small>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-success approve-btn" data-id="{{ $job->id }}" data-type="job">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn btn-sm btn-danger reject-btn" data-id="{{ $job->id }}" data-type="job">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p><strong>Description:</strong></p>
                                <p>{{ $job->job_description }}</p>
                                
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <p><strong>Industry:</strong><br>{{ $job->industry }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Job Type:</strong><br>{{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Location:</strong><br>{{ $job->work_location }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p><strong>Salary Range:</strong><br>{{ $job->salary_range }}</p>
                                        <p><strong>Deadline:</strong><br>{{ $job->app_deadline->format('M d, Y') }}</p>
                                        <p><strong>Posted:</strong><br>{{ $job->created_at->format('M d, Y') }}</p>
                                        <p><strong>User Email:</strong><br>{{ $job->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Offers Tab -->
    <div class="tab-pane fade" id="offers" role="tabpanel">
        @if($pendingOffers->isEmpty())
            <div class="alert alert-info">
                No pending offers for approval.
            </div>
        @else
            @foreach($pendingOffers as $offer)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $offer->product_name }}</h5>
                            <small class="text-muted">Posted by: {{ $offer->user->name }}</small>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-success approve-btn" data-id="{{ $offer->id }}" data-type="offer">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn btn-sm btn-danger reject-btn" data-id="{{ $offer->id }}" data-type="offer">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if($offer->product_image)
                                    <img src="{{ asset('storage/' . $offer->product_image) }}" 
                                         alt="{{ $offer->product_name }}" 
                                         class="img-fluid rounded">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                                         style="height: 200px;">
                                        <i class="fas fa-image fa-3x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <p><strong>Description:</strong></p>
                                <p>{{ $offer->description }}</p>
                                
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <p><strong>Category:</strong><br>{{ $offer->category }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Price:</strong><br>${{ number_format($offer->price, 2) }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Discount:</strong><br>{{ $offer->discount }}%</p>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <p><strong>Expiry Date:</strong><br>{{ $offer->expiry_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Final Price:</strong><br>
                                            <span class="text-success fw-bold">${{ number_format($offer->final_price, 2) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this content?</p>
                <div class="mb-3">
                    <label for="approveReason" class="form-label">Reason (Optional):</label>
                    <textarea class="form-control" id="approveReason" rows="3" 
                              placeholder="Enter reason for approval..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmApprove">Approve</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this content?</p>
                <div class="mb-3">
                    <label for="rejectReason" class="form-label">Reason (Required):</label>
                    <textarea class="form-control" id="rejectReason" rows="3" 
                              placeholder="Enter reason for rejection..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Reject</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentId, currentType;
    
    // Approve button click
    $('.approve-btn').click(function() {
        currentId = $(this).data('id');
        currentType = $(this).data('type');
        $('#approveModal').modal('show');
    });
    
    // Reject button click
    $('.reject-btn').click(function() {
        currentId = $(this).data('id');
        currentType = $(this).data('type');
        $('#rejectModal').modal('show');
    });
    
    // Confirm approve
    $('#confirmApprove').click(function() {
        const reason = $('#approveReason').val();
        const url = currentType === 'job' 
            ? `/admin/jobs/${currentId}/approve`
            : `/admin/offers/${currentId}/approve`;
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                reason: reason
            },
            success: function(response) {
                showToast('success', response.message);
                $('#approveModal').modal('hide');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                showToast('error', xhr.responseJSON?.message || 'An error occurred');
            }
        });
    });
    
    // Confirm reject
    $('#confirmReject').click(function() {
        const reason = $('#rejectReason').val();
        if (!reason.trim()) {
            showToast('error', 'Please enter a reason for rejection');
            return;
        }
        
        const url = currentType === 'job' 
            ? `/admin/jobs/${currentId}/reject`
            : `/admin/offers/${currentId}/reject`;
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                reason: reason
            },
            success: function(response) {
                showToast('success', response.message);
                $('#rejectModal').modal('hide');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                showToast('error', xhr.responseJSON?.message || 'An error occurred');
            }
        });
    });
    
    // Reset modals on hide
    $('#approveModal, #rejectModal').on('hidden.bs.modal', function() {
        $('#approveReason, #rejectReason').val('');
    });
    
    function showToast(type, message) {
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        
        $('#toast-container').append(toast);
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        setTimeout(() => toast.remove(), 5000);
    }
});
</script>
@endpush