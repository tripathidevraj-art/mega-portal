<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update on Your Application</title>
</head>
<body>
    <h2 style="color: #dc3545;">Update on Your Application</h2>
    
    <p>Hello {{ $application->user->full_name }},</p>
    
    <p>Thank you for your interest in the following position. We regret to inform you that your application was not selected for the next stage:</p>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3>{{ $application->job->job_title }}</h3>
        <p><strong>Company/Poster:</strong> {{ $application->job->user->full_name }}</p>
        <p><strong>Location:</strong> {{ $application->job->work_location }}</p>
        <p><strong>Job Type:</strong> {{ ucfirst(str_replace('_', ' ', $application->job->job_type)) }}</p>
        <p><strong>Decision Date:</strong> {{ $application->updated_at->format('F d, Y') }}</p>
    </div>
    
    <div style="background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #dc3545;">
        <h4 style="color: #721c24;">Application Status: <span>Not Selected</span></h4>
        <p>We appreciate the time and effort you put into your application.</p>
        @if($application->admin_notes)
            <p><strong>Feedback from employer:</strong><br>{{ $application->admin_notes }}</p>
        @endif
    </div>
    
    <p>Don't get discouraged! New opportunities are posted daily.</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('jobs.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Browse New Jobs
        </a>
    </p>
    
    <p>Best regards,<br>Job & Offer Portal Team</p>
</body>
</html>