<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Job Posting Has Been Removed</title>
</head>
<body>
    <h2 style="color: #dc3545;">Your Job Posting Has Been Removed</h2>
    
    <p>Hello {{ $job->user->full_name }},</p>
    
    <p>We regret to inform you that your job posting has been removed following a user report:</p>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3>{{ $job->job_title }}</h3>
        <p><strong>Industry:</strong> {{ $job->industry }}</p>
        <p><strong>Location:</strong> {{ $job->work_location }}</p>
        <p><strong>Posted On:</strong> {{ $job->created_at->format('F d, Y') }}</p>
    </div>

    @if($adminNotes)
        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h4 style="color: #856404;">Admin Notes:</h4>
            <p>{{ $adminNotes }}</p>
        </div>
    @endif

    <p>This action was taken to maintain the integrity and safety of our platform. If you believe this was a mistake, please contact our support team.</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('user.jobs.my-jobs') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            View My Jobs
        </a>
    </p>
    
    <p>Thank you for your understanding.<br>Job & Offer Portal Team</p>
</body>
</html>