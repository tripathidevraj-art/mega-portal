<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Job Application Received</title>
</head>
<body>
    <h2>New Job Application Received</h2>
    
    <p>Hello {{ $application->job->user->full_name }},</p>
    
    <p>You have received a new application for your job posting:</p>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3>{{ $application->job->job_title }}</h3>
        <p><strong>Applicant:</strong> {{ $application->user->full_name }}</p>
        <p><strong>Applicant Email:</strong> {{ $application->user->email }}</p>
        <p><strong>Applied On:</strong> {{ $application->created_at->format('F d, Y h:i A') }}</p>
    </div>
    
    @if($application->cover_letter)
    <div style="margin: 20px 0;">
        <h4>Cover Letter:</h4>
        <p>{{ $application->cover_letter }}</p>
    </div>
    @endif
    
    <p>You can view all applications for this job from your dashboard.</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('user.jobs.my-jobs') }}" style="background: #4361ee; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            View Applications
        </a>
    </p>
    
    <p>Best regards,<br>Job & Offer Portal Team</p>
</body>
</html>