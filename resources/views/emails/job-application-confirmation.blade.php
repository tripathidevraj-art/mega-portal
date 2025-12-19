<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Submitted Successfully</title>
</head>
<body>
    <h2>Application Submitted Successfully</h2>
    
    <p>Hello {{ $application->user->full_name }},</p>
    
    <p>Your application has been submitted successfully for the following position:</p>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3>{{ $application->job->job_title }}</h3>
        <p><strong>Company/Poster:</strong> {{ $application->job->user->full_name }}</p>
        <p><strong>Location:</strong> {{ $application->job->work_location }}</p>
        <p><strong>Job Type:</strong> {{ ucfirst(str_replace('_', ' ', $application->job->job_type)) }}</p>
        <p><strong>Applied On:</strong> {{ $application->created_at->format('F d, Y h:i A') }}</p>
    </div>
    
    <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h4>Application Status: <span style="color: #ff9800;">Pending Review</span></h4>
        <p>The job poster will review your application and contact you if you're shortlisted.</p>
    </div>
    
    <p>You can track your application status from your dashboard.</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('user.jobs.applications') }}" style="background: #4361ee; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Track Applications
        </a>
    </p>
    
    <p>Best regards,<br>Job & Offer Portal Team</p>
</body>
</html>