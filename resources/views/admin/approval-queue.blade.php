@extends('layouts.app')

@section('title', 'Approval Queue')

@section('header', 'Approval Queue')
@section('subheader', 'Pending Jobs & Offers')

@section('content')
<style>
    .view-toggle {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 10;
    }
    .approval-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .approval-card:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .card-header { background: #f8f9fa; padding: 1rem 1.25rem !important; }
    .card-body { padding: 1.25rem !important; }
    .badge-expiring { background: #fff3cd; color: #856404; }
    .meta-item { font-size: 0.875rem; color: #6c757d; }
    .meta-value { font-weight: 600; color: #212529; }
    .price-highlight { color: #198754; font-weight: 700; }
    .expiry-warning { color: #dc3545; font-weight: 600; }
    .img-placeholder { background: #f1f3f5; display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 2.5rem; }
    
    /* List View Table */
    .list-table thead th {
        background: #f8f9fa;
        font-weight: 600;
        border-top: none;
    }
    .list-table td, .list-table th {
        vertical-align: middle !important;
        padding: 0.75rem !important;
    }
    .list-description {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
</style>

<!-- Tabs -->
<div class="position-relative">
    <ul class="nav nav-tabs mb-4" id="approvalTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobs">
                Jobs <span class="badge bg-warning ms-1">{{ $pendingJobs->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="offers-tab" data-bs-toggle="tab" data-bs-target="#offers">
                Offers <span class="badge bg-warning ms-1">{{ $pendingOffers->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Toggle View Buttons -->
    <div class="view-toggle btn-group" role="group">
        <button type="button" class="btn btn-sm btn-outline-secondary" id="listViewBtn" title="List View">
            <i class="fas fa-list"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary active" id="gridViewBtn" title="Grid View">
            <i class="fas fa-th-large"></i>
        </button>
    </div>
</div>

<div class="tab-content">
    <!-- Jobs Tab -->
    <div class="tab-pane fade show active" id="jobs">
            <form method="GET" class="row g-3 mb-4">
        <input type="hidden" name="tab" value="jobs">
        
        <div class="col-md-3">
            <input type="text" name="job_search" value="{{ request('job_search') }}" 
                   class="form-control" placeholder="Search job title or industry">
        </div>
        <div class="col-md-2">
            <select name="job_type" class="form-select">
                <option value="">All Types</option>
                <option value="full_time" {{ request('job_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                <option value="part_time" {{ request('job_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                <option value="remote" {{ request('job_type') == 'remote' ? 'selected' : '' }}>Remote</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="job_deadline_from" value="{{ request('job_deadline_from') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="date" name="job_deadline_to" value="{{ request('job_deadline_to') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <select name="job_sort" class="form-select">
                <option value="created_at_desc" {{ request('job_sort') == 'created_at_desc' ? 'selected' : '' }}>Newest First</option>
                <option value="created_at_asc" {{ request('job_sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest First</option>
                <option value="deadline_asc" {{ request('job_sort') == 'deadline_asc' ? 'selected' : '' }}>Deadline (Soonest)</option>
                <option value="title_asc" {{ request('job_sort') == 'title_asc' ? 'selected' : '' }}>Title A-Z</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter"></i>
            </button>
        </div>
        @if(request()->filled(['job_search', 'job_type', 'job_deadline_from', 'job_deadline_to', 'job_sort']))
            <div class="col-md-1 d-flex align-items-end">
                <a href="{{ route('admin.approval-queue') }}?tab=jobs" class="btn btn-secondary w-100">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        @endif
    </form>
        <!-- Grid View -->
        <div id="jobsGrid" class="row g-4">
            @if($pendingJobs->isEmpty())
                <div class="col-12 text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No pending jobs for approval</h5>
                </div>
            @else
                @foreach($pendingJobs as $job)
                <div class="col-12 col-md-6 col-xl-4 job-item">
                    <div class="approval-card">
                        <div class="card-header d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1 fs-5">{{ $job->job_title }}</h5>
                                <p class="meta-item mb-1">by {{ $job->user->name }} • {{ $job->created_at->format('M d') }}</p>
                                @if($job->app_deadline->lt(now()->addDays(2)))
                                    <span class="badge badge-expiring">Expiring Soon</span>
                                @endif
                            </div>
                            <div>
                                <button class="btn btn-sm btn-success approve-btn" data-id="{{ $job->id }}" data-type="job">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-danger reject-btn mt-1" data-id="{{ $job->id }}" data-type="job">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">{{ Str::limit($job->job_description, 100) }}</p>
                            <div class="row g-2">
                                <div class="col-6"><p class="meta-item mb-1">Industry</p><p class="meta-value">{{ $job->industry }}</p></div>
                                <div class="col-6"><p class="meta-item mb-1">Type</p><p class="meta-value">{{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</p></div>
                                <div class="col-6"><p class="meta-item mb-1">Location</p><p class="meta-value">{{ $job->work_location }}</p></div>
                                <div class="col-6"><p class="meta-item mb-1">Deadline</p><p class="meta-value expiry-warning">{{ $job->app_deadline->format('M d, Y') }}</p></div>
                                <div class="col-12"><p class="meta-item mb-1">Salary</p><p class="meta-value">{{ $job->salary_range }}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- List View -->
        <div id="jobsList" class="d-none">
            @if($pendingJobs->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No pending jobs for approval</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover list-table">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Posted By</th>
                                <th>Industry</th>
                                <th>Deadline</th>
                                <th>Salary</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingJobs as $job)
                            <tr>
                                <td>
                                    <strong>{{ $job->job_title }}</strong><br>
                                    <small class="text-muted list-description">{{ Str::limit($job->job_description, 50) }}</small>
                                    @if($job->app_deadline->lt(now()->addDays(2)))
                                        <span class="badge badge-expiring ms-1">Expiring Soon</span>
                                    @endif
                                </td>
                                <td>{{ $job->user->name }}<br><small>{{ $job->created_at->format('M d') }}</small></td>
                                <td>{{ $job->industry }}</td>
                                <td class="expiry-warning">{{ $job->app_deadline->format('M d, Y') }}</td>
                                <td>{{ $job->salary_range }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success approve-btn" data-id="{{ $job->id }}" data-type="job">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger reject-btn mt-1" data-id="{{ $job->id }}" data-type="job">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Offers Tab -->
    <div class="tab-pane fade" id="offers">
            <form method="GET" class="row g-3 mb-4">
        <input type="hidden" name="tab" value="offers">
        
        <div class="col-md-3">
            <input type="text" name="offer_search" value="{{ request('offer_search') }}" 
                   class="form-control" placeholder="Search product or category">
        </div>
        <div class="col-md-2">
            <select name="offer_category" class="form-select">
                <option value="">All Categories</option>
                <option value="Electronics" {{ request('offer_category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="Fashion" {{ request('offer_category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                <option value="Health" {{ request('offer_category') == 'Health' ? 'selected' : '' }}>Health</option>
                <option value="Home" {{ request('offer_category') == 'Home' ? 'selected' : '' }}>Home</option>
                <!-- Add more as per your DB -->
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="offer_min_price" value="{{ request('offer_min_price') }}" 
                   class="form-control" placeholder="Min Price">
        </div>
        <div class="col-md-2">
            <input type="number" name="offer_max_price" value="{{ request('offer_max_price') }}" 
                   class="form-control" placeholder="Max Price">
        </div>
        <div class="col-md-2">
            <select name="offer_sort" class="form-select">
                <option value="created_at_desc" {{ request('offer_sort') == 'created_at_desc' ? 'selected' : '' }}>Newest First</option>
                <option value="created_at_asc" {{ request('offer_sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest First</option>
                <option value="expiry_asc" {{ request('offer_sort') == 'expiry_asc' ? 'selected' : '' }}>Expiry (Soonest)</option>
                <option value="price_asc" {{ request('offer_sort') == 'price_asc' ? 'selected' : '' }}>Price Low-High</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter"></i>
            </button>
        </div>
        @if(request()->filled(['offer_search', 'offer_category', 'offer_min_price', 'offer_max_price', 'offer_sort']))
            <div class="col-md-1 d-flex align-items-end">
                <a href="{{ route('admin.approval-queue') }}?tab=offers" class="btn btn-secondary w-100">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        @endif
    </form>
        <!-- Grid View -->
        <div id="offersGrid" class="row g-4">
            @if($pendingOffers->isEmpty())
                <div class="col-12 text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No pending offers for approval</h5>
                </div>
            @else
                @foreach($pendingOffers as $offer)
                <div class="col-12 col-md-6 col-xl-4 offer-item">
                    <div class="approval-card">
                        <div class="card-header d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1 fs-5">{{ $offer->product_name }}</h5>
                                <p class="meta-item mb-1">by {{ $offer->user->name }} • {{ $offer->created_at->format('M d') }}</p>
                                @if($offer->expiry_date->lt(now()->addDays(2)))
                                    <span class="badge badge-expiring">Expiring Soon</span>
                                @endif
                            </div>
                            <div>
                                <button class="btn btn-sm btn-success approve-btn" data-id="{{ $offer->id }}" data-type="offer">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-danger reject-btn mt-1" data-id="{{ $offer->id }}" data-type="offer">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-5">
                                    @if($offer->product_image)
                                        <img src="{{ asset('storage/' . $offer->product_image) }}" 
                                             alt="{{ $offer->product_name }}" 
                                             class="img-fluid rounded w-100" style="aspect-ratio:1/1; object-fit:cover;">
                                    @else
                                        <div class="img-placeholder rounded w-100" style="aspect-ratio:1/1;">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12 col-md-7">
                                    <p class="text-muted small mb-2">{{ Str::limit($offer->description, 100) }}</p>
                                    <div class="row g-2">
                                        <div class="col-6"><p class="meta-item mb-1">Category</p><p class="meta-value">{{ $offer->category }}</p></div>
                                        <div class="col-6"><p class="meta-item mb-1">Discount</p><p class="text-danger meta-value">{{ $offer->discount }}%</p></div>
                                        <div class="col-6"><p class="meta-item mb-1">Expiry</p><p class="expiry-warning meta-value">{{ $offer->expiry_date->format('M d, Y') }}</p></div>
                                        <div class="col-6"><p class="meta-item mb-1">Price</p><p class="meta-value">${{ number_format($offer->price, 2) }}</p></div>
                                        <div class="col-12"><p class="meta-item mb-1">Final Price</p><p class="price-highlight meta-value">${{ number_format($offer->final_price, 2) }}</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- List View -->
        <div id="offersList" class="d-none">
            @if($pendingOffers->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No pending offers for approval</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover list-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Expiry</th>
                                <th>Final Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingOffers as $offer)
                            <tr>
                                <td>
                                    <strong>{{ $offer->product_name }}</strong><br>
                                    <small class="text-muted list-description">{{ Str::limit($offer->description, 50) }}</small>
                                    @if($offer->expiry_date->lt(now()->addDays(2)))
                                        <span class="badge badge-expiring ms-1">Expiring Soon</span>
                                    @endif
                                </td>
                                <td>{{ $offer->category }}</td>
                                <td>${{ number_format($offer->price, 2) }}</td>
                                <td class="text-danger">{{ $offer->discount }}%</td>
                                <td class="expiry-warning">{{ $offer->expiry_date->format('M d, Y') }}</td>
                                <td class="price-highlight">${{ number_format($offer->final_price, 2) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success approve-btn" data-id="{{ $offer->id }}" data-type="offer">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger reject-btn mt-1" data-id="{{ $offer->id }}" data-type="offer">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
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
  let viewMode = localStorage.getItem('approval_view') || 'grid';
    updateViewMode(viewMode);

    $('#listViewBtn').click(function() {
        updateViewMode('list');
        localStorage.setItem('approval_view', 'list');
    });

    $('#gridViewBtn').click(function() {
        updateViewMode('grid');
        localStorage.setItem('approval_view', 'grid');
    });

    function updateViewMode(mode) {
        $('#listViewBtn, #gridViewBtn').removeClass('active');
        if (mode === 'list') {
            $('#listViewBtn').addClass('active');
            $('#jobsGrid, #offersGrid').addClass('d-none');
            $('#jobsList, #offersList').removeClass('d-none');
        } else {
            $('#gridViewBtn').addClass('active');
            $('#jobsGrid, #offersGrid').removeClass('d-none');
            $('#jobsList, #offersList').addClass('d-none');
        }
    }

    // Use event delegation (works even if content is dynamic)
    $(document).on('click', '.approve-btn', function(e) {
        e.preventDefault();
        currentId = $(this).data('id');
        currentType = $(this).data('type');
        $('#approveModal').modal('show');
    });

    $(document).on('click', '.reject-btn', function(e) {
        e.preventDefault();
        currentId = $(this).data('id');
        currentType = $(this).data('type');
        $('#rejectModal').modal('show');
    });

    // Confirm approve
    $('#confirmApprove').click(function() {
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Processing...');

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
                $('#approveModal').modal('hide');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
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

        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Processing...');

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
                $('#rejectModal').modal('hide');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Reset modals
    $('#approveModal, #rejectModal').on('hidden.bs.modal', function() {
        $('#approveReason, #rejectReason').val('');
    });
    
    // Toast helper
    function showToast(type, message) {
        let toastContainer = $('#toast-container');
        if (toastContainer.length === 0) {
            $('body').append('<div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;"></div>');
            toastContainer = $('#toast-container');
        }
        
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        
        toastContainer.append(toast);
        const bsToast = new bootstrap.Toast(toast[0], { delay: 4000 });
        bsToast.show();
        
        toast.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }
});
</script>
@endpush