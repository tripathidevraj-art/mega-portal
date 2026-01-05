@extends('layouts.app')

@section('title', 'Expired Content')

@section('header', 'Expired Content')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Expired Content</h6>
                <ul class="nav nav-pills card-header-pills" id="expiredTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#jobs-tab">Expired Jobs ({{ $expiredJobs->count() }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#offers-tab">Expired Offers ({{ $expiredOffers->count() }})</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- EXPIRED JOBS TAB -->
                    <div class="tab-pane fade show active" id="jobs-tab">
                        <!-- Filters -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <input type="text" name="job_search" value="{{ request('job_search') }}" 
                                       class="form-control" placeholder="Search title or industry">
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
                                <input type="date" name="job_deadline_from" value="{{ request('job_deadline_from') }}" class="form-control" placeholder="Deadline From">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="job_deadline_to" value="{{ request('job_deadline_to') }}" class="form-control" placeholder="Deadline To">
                            </div>
                            <div class="col-md-2">
                                <select name="job_sort" class="form-select">
                                    <option value="created_at" {{ request('job_sort') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                    <option value="app_deadline" {{ request('job_sort') == 'app_deadline' ? 'selected' : '' }}>Expire On</option>
                                    <option value="views" {{ request('job_sort') == 'views' ? 'selected' : '' }}>Views</option>
                                    <option value="job_title" {{ request('job_sort') == 'job_title' ? 'selected' : '' }}>Title</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                            @if(request()->anyFilled(['job_search', 'job_type', 'job_deadline_from', 'job_deadline_to', 'job_sort']))
                                <div class="col-md-1 d-flex align-items-end">
                                    <a href="{{ route('admin.expired-content') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                        </form>

                        <!-- Table -->
                        @if($expiredJobs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                @php
                                                    $sortField = 'job_title';
                                                    $currentSort = request('job_sort', 'created_at');
                                                    $currentOrder = request('job_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['job_sort' => $sortField, 'job_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Job Title
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                @php
                                                    $sortField = 'industry';
                                                    $currentSort = request('job_sort', 'created_at');
                                                    $currentOrder = request('job_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['job_sort' => $sortField, 'job_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Industry
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                @php
                                                    $sortField = 'job_type';
                                                    $currentSort = request('job_sort', 'created_at');
                                                    $currentOrder = request('job_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['job_sort' => $sortField, 'job_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Type
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                @php
                                                    $sortField = 'work_location';
                                                    $currentSort = request('job_sort', 'created_at');
                                                    $currentOrder = request('job_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['job_sort' => $sortField, 'job_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Location
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                @php
                                                    $sortField = 'app_deadline';
                                                    $currentSort = request('job_sort', 'created_at');
                                                    $currentOrder = request('job_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['job_sort' => $sortField, 'job_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Expire On
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Posted By</th>
                                            <th>
                                                @php
                                                    $sortField = 'views';
                                                    $currentSort = request('job_sort', 'created_at');
                                                    $currentOrder = request('job_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['job_sort' => $sortField, 'job_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Views
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expiredJobs as $job)
                                            <tr>
                                                <td>
                                                    <strong>{{ $job->job_title }}</strong><br>
                                                    <small class="text-muted">{{ Str::limit($job->job_description, 40) }}</small>
                                                </td>
                                                <td>{{ $job->industry ?? '—' }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $job->work_location ?? 'Remote' }}</td>
                                                <td>
                                                    @if($job->app_deadline)
                                                        {{ $job->app_deadline->format('M d, Y') }}
                                                    @else
                                                        <em>N/A</em>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($job->user)
                                                        {{ $job->user->full_name }}<br>
                                                        <small>{{ $job->user->email }}</small>
                                                    @else
                                                        <em>Deleted User</em>
                                                    @endif
                                                </td>
                                                <td>{{ $job->views }}</td>
                                                <td>
                                                    <a href="{{ route('admin.user.show', $job->user_id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-user"></i> User
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No expired jobs found.</p>
                            </div>
                        @endif
                    </div>

                    <!-- EXPIRED OFFERS TAB -->
                    <div class="tab-pane fade" id="offers-tab">
                        <!-- Filters -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <input type="text" name="offer_search" value="{{ request('offer_search') }}" 
                                       class="form-control" placeholder="Search product or category">
                            </div>
                            <div class="col-md-2">
                                <select name="offer_discount" class="form-select">
                                    <option value="">All Offers</option>
                                    <option value="has_discount" {{ request('offer_discount') == 'has_discount' ? 'selected' : '' }}>With Discount</option>
                                    <option value="no_discount" {{ request('offer_discount') == 'no_discount' ? 'selected' : '' }}>No Discount</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="offer_expiry_from" value="{{ request('offer_expiry_from') }}" class="form-control" placeholder="Expiry From">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="offer_expiry_to" value="{{ request('offer_expiry_to') }}" class="form-control" placeholder="Expiry To">
                            </div>
                            <div class="col-md-2">
                                <select name="offer_sort" class="form-select">
                                    <option value="created_at" {{ request('offer_sort') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                    <option value="expiry_date" {{ request('offer_sort') == 'expiry_date' ? 'selected' : '' }}>Expiry Date</option>
                                    <option value="price" {{ request('offer_sort') == 'price' ? 'selected' : '' }}>Price</option>
                                    <option value="views" {{ request('offer_sort') == 'views' ? 'selected' : '' }}>Views</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                            @if(request()->anyFilled(['offer_search', 'offer_discount', 'offer_expiry_from', 'offer_expiry_to', 'offer_sort']))
                                <div class="col-md-1 d-flex align-items-end">
                                    <a href="{{ route('admin.expired-content') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                        </form>

                        <!-- Table -->
                        @if($expiredOffers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                @php
                                                    $sortField = 'product_name';
                                                    $currentSort = request('offer_sort', 'created_at');
                                                    $currentOrder = request('offer_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['offer_sort' => $sortField, 'offer_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Product
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                @php
                                                    $sortField = 'category';
                                                    $currentSort = request('offer_sort', 'created_at');
                                                    $currentOrder = request('offer_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['offer_sort' => $sortField, 'offer_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Category
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                @php
                                                    $sortField = 'price';
                                                    $currentSort = request('offer_sort', 'created_at');
                                                    $currentOrder = request('offer_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['offer_sort' => $sortField, 'offer_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Price
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Discount</th>
                                            <th>
                                                @php
                                                    $sortField = 'expiry_date';
                                                    $currentSort = request('offer_sort', 'created_at');
                                                    $currentOrder = request('offer_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['offer_sort' => $sortField, 'offer_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Expire On
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Posted By</th>
                                            <th>
                                                @php
                                                    $sortField = 'views';
                                                    $currentSort = request('offer_sort', 'created_at');
                                                    $currentOrder = request('offer_order', 'desc');
                                                    $isActive = $currentSort === $sortField;
                                                    $newOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
                                                @endphp
                                                <a href="{{ request()->fullUrlWithQuery(['offer_sort' => $sortField, 'offer_order' => $newOrder, 'page' => 1]) }}" class="text-decoration-none">
                                                    Views
                                                    @if($isActive)
                                                        @if($currentOrder === 'asc')
                                                            <i class="fas fa-sort-up text-primary"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-muted"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expiredOffers as $offer)
                                            <tr>
                                                <td>
                                                    <strong>{{ $offer->product_name }}</strong><br>
                                                    <small class="text-muted">{{ Str::limit($offer->description, 40) }}</small>
                                                    @if($offer->product_image)
                                                        <br><img src="{{ Storage::url($offer->product_image) }}" width="50" class="mt-2 rounded">
                                                    @endif
                                                </td>
                                                <td>{{ $offer->category ?? '—' }}</td>
                                                <td>₹{{ number_format($offer->price, 2) }}</td>
                                                <td>
                                                    @if($offer->discount > 0)
                                                        <span class="badge bg-success">-{{ $offer->discount }}%</span>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($offer->expiry_date)
                                                        {{ $offer->expiry_date->format('M d, Y') }}
                                                    @else
                                                        <em>N/A</em>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($offer->user)
                                                        {{ $offer->user->full_name }}<br>
                                                        <small>{{ $offer->user->email }}</small>
                                                    @else
                                                        <em>Deleted User</em>
                                                    @endif
                                                </td>
                                                <td>{{ $offer->views }}</td>
                                                <td>
                                                    <a href="{{ route('admin.user.show', $offer->user_id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-user"></i> User
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No expired offers found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection