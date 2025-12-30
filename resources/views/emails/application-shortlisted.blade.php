<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Congratulations! You've Been Shortlisted</title>
</head>
<body>
    <h2 style="color: #28a745;">Congratulations! You've Been Shortlisted</h2>
    
    <p>Hello {{ $application->user->full_name }},</p>
    
    <p>We're pleased to inform you that you've been shortlisted for the following position:</p>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3>{{ $application->job->job_title }}</h3>
        <p><strong>Company/Poster:</strong> {{ $application->job->user->full_name }}</p>
        <p><strong>Location:</strong> {{ $application->job->work_location }}</p>
        <p><strong>Job Type:</strong> {{ ucfirst(str_replace('_', ' ', $application->job->job_type)) }}</p>
        <p><strong>Shortlisted On:</strong> {{ $application->updated_at->format('F d, Y') }}</p>
    </div>
    
    <div style="background: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;">
        <h4 style="color: #28a745;">Application Status: <span>Shortlisted</span></h4>
        <p>The employer will contact you soon to discuss next steps (interview, assessment, etc.).</p>
        @if($application->admin_notes)
            <p><strong>Next Steps:</strong><br>{{ $application->admin_notes }}</p>
        @endif
    </div>
    
    <p>Make sure your contact information is up to date in your profile.</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('user.profile') }}" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Update Profile
        </a>
    </p>
    
    <p>Best regards,<br>Job & Offer Portal Team</p>
</body>
</html>