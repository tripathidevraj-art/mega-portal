@extends('layouts.app')
@section('title', 'All Reported Jobs')
@section('header', 'All Reported Jobs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>All Job Reports</h5>
                    <a href="{{ route('admin.reported-jobs') }}" class="btn btn-sm btn-outline-primary">
                        Pending Only
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Job</th>
                                <th>Reported By</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Reported At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td>
                                    @if($report->job)
                                        <strong>{{ $report->job->job_title }}</strong>
                                        @if($report->job->status === 'reported')
                                            <span class="badge bg-danger ms-2">Hidden</span>
                                        @elseif($report->job->status === 'rejected')
                                            <span class="badge bg-secondary ms-2">Rejected</span>
                                        @elseif($report->job->status === 'approved')
                                            <span class="badge bg-success ms-2">Active</span>
                                        @endif
                                    @else
                                        <strong class="text-muted">[Job Removed]</strong>
                                        <span class="badge bg-dark ms-2">Orphaned</span>
                                    @endif
                                </td>
                                <td>{{ $report->reporter->full_name }}</td>
                                <td>
                                    {{ \App\Models\JobReport::getReasonLabel($report->reason) }}
                                    @if($report->details)
                                        <div class="mt-2">
                                            <small class="text-muted" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top"
                                                title="{{ $report->details }}">
                                                <i class="fas fa-comment me-1"></i>
                                                "{{ Str::limit($report->details, 60) }}"
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($report->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-success">Resolved</span>
                                    @endif
                                </td>
                                <td>{{ $report->created_at->format('M d, Y') }}</td>
                                <td>
                                @if($report->job && $report->job->status === 'reported')
                                    <button type="button" 
                                            class="btn btn-sm btn-success restore-btn"
                                            data-action="{{ route('admin.reported-jobs.restore', $report->id) }}"
                                            data-message="Restore this job to public listings?">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                    @elseif($report->job)
                                        <span class="text-success">Visible</span>
                                    @else
                                        <span class="text-muted">Orphaned</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No reports found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Custom Confirmation Modal --}}
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage">Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmActionButton">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentFormAction = null;
    new bootstrap.Tooltip(document.body, { selector: '[data-bs-toggle="tooltip"]' });
    // Open confirmation modal
    document.querySelectorAll('.restore-btn').forEach(button => {
        button.addEventListener('click', function () {
            const message = this.getAttribute('data-message');
            currentFormAction = this.getAttribute('data-action');

            document.getElementById('confirmationMessage').textContent = message;
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        });
    });

    // Handle confirm button click
    document.getElementById('confirmActionButton').addEventListener('click', function () {
        if (currentFormAction) {
            // Create a temporary form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = currentFormAction;

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Add method spoofing (if needed)
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'POST';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
@endpush