@extends('layouts.app')

@section('title', 'Reported Jobs')
@section('header', 'Reported Jobs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4">Pending Job Reports</h5>

                @if($reports->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">No reported jobs at the moment.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Job</th>
                                    <th>Reported By</th>
                                    <th>Reason</th>
                                    <th>Reported At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                <tr>
                                    <td>
                                        <strong>{{ $report->job->job_title }}</strong><br>
                                        <small class="text-muted">{{ $report->job->industry }} • {{ $report->job->work_location }}</small>
                                    </td>
                                    <td>
                                        {{ $report->reporter->full_name }}<br>
                                        <small class="text-muted">{{ $report->reporter->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ \App\Models\JobReport::getReasonLabel($report->reason) }}
                                        </span>
                                        @if($report->details)
                                            <br><small class="text-muted">{{ Str::limit($report->details, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $report->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <!-- Button triggers modal with data attributes -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary btn-action"
                                                data-bs-toggle="modal"
                                                data-bs-target="#actionModal"
                                                data-report-id="{{ $report->id }}"
                                                data-job-title="{{ $report->job->job_title }}">
                                            Take Action
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ✅ SINGLE MODAL OUTSIDE TABLE --}}
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Handle Report: <span id="modal-job-title"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="actionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Choose Action</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" value="delete_job" id="deleteAction" required>
                            <label class="form-check-label" for="deleteAction">
                                <strong>Delete Job</strong> – Remove job and notify owner
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" value="dismiss_report" id="dismissAction" required>
                            <label class="form-check-label" for="dismissAction">
                                <strong>Dismiss Report</strong> – Keep job, mark report as reviewed
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Admin Notes (Optional)</label>
                        <textarea name="admin_notes" class="form-control" rows="2" placeholder="Internal notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Action</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('actionModal');
    const form = document.getElementById('actionForm');
    const jobTitleSpan = document.getElementById('modal-job-title');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const reportId = button.getAttribute('data-report-id');
        const jobTitle = button.getAttribute('data-job-title');

        // Update modal title
        jobTitleSpan.textContent = jobTitle;

        // Update form action URL
        form.action = '/admin/reported-jobs/' + reportId + '/take-action';
    });
});
</script>
@endpush