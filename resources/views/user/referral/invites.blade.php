@extends('layouts.app')

@section('title', 'My Invites')

@section('content')
<div class="container py-4">
    <h2>My Referral Invites</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h5>Invited</h5>
                    <h2 class="text-info">{{ $stats['invited'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h5>Accepted</h5>
                    <h2 class="text-success">{{ $stats['accepted'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Type</th>
                <th>Status</th>
                <th>Invited At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invites as $invite)
                <tr>
                    <td>{{ $invite->name }}</td>
                    <td>{{ $invite->contact }}</td>
                    <td>
                        @if($invite->type === 'whatsapp')
                            <i class="fab fa-whatsapp text-success"></i> WhatsApp
                        @else
                            <i class="fas fa-envelope text-primary"></i> Email
                        @endif
                    </td>
                    <td>
                        @if($invite->accepted)
                            <span class="badge bg-success">Accepted</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>{{ $invite->created_at->diffForHumans() }}</td>
                    <td>
                        @if(!$invite->accepted)
                            <button class="btn btn-sm btn-outline-secondary" onclick="resendInvite({{ $invite->id }})">
                                Re-send
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No invites sent yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $invites->links() }}
</div>

<script>
function resendInvite(inviteId) {
    if(confirm('Re-send invite?')) {
        fetch(`/referral/resend/${inviteId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(() => location.reload());
    }
}
</script>
@endsection