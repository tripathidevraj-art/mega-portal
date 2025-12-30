<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Application Has Been Reviewed</title>
</head>
<body>
    <h2>Your Application Has Been Reviewed</h2>
    
    <p>Hello {{ $application->user->full_name }},</p>
    
    <p>The job poster has reviewed your application for the following position:</p>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3>{{ $application->job->job_title }}</h3>
        <p><strong>Company/Poster:</strong> {{ $application->job->user->full_name }}</p>
        <p><strong>Location:</strong> {{ $application->job->work_location }}</p>
        <p><strong>Job Type:</strong> {{ ucfirst(str_replace('_', ' ', $application->job->job_type)) }}</p>
        <p><strong>Reviewed On:</strong> {{ $application->updated_at->format('F d, Y') }}</p>
    </div>
    
    <div style="background: #fff8e1; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
        <h4>Application Status: <span style="color: #ff9800;">Under Review</span></h4>
        <p>Your application is being carefully evaluated. You'll be contacted if shortlisted.</p>
        @if($application->admin_notes)
            <p><strong>Message from employer:</strong><br>{{ $application->admin_notes }}</p>
        @endif
    </div>
    
    <p>You can track your application status from your dashboard.</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('user.jobs.applications') }}" style="background: #4361ee; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            View Applications
        </a>
    </p>
    
    <p>Best regards,<br>Job & Offer Portal Team</p>
</body>
</html>