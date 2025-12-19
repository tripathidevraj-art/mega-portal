<!DOCTYPE html>
<html>
<head>
    <title>Job Approved</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4361ee; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 5px; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #4361ee; color: white; text-decoration: none; border-radius: 5px; }
        .job-details { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Job Posting Approved</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $job->user->full_name }},</p>
            
            <p>We're pleased to inform you that your job posting has been approved by our admin team.</p>
            
            <div class="job-details">
                <h3>{{ $job->job_title }}</h3>
                <p><strong>Industry:</strong> {{ $job->industry }}</p>
                <p><strong>Location:</strong> {{ $job->work_location }}</p>
                <p><strong>Deadline:</strong> {{ $job->app_deadline->format('F d, Y') }}</p>
                @if($reason)
                <p><strong>Admin Note:</strong> {{ $reason }}</p>
                @endif
            </div>
            
            <p>Your job is now visible to all users on our platform.</p>
            
            <p style="text-align: center;">
                <a href="{{ route('jobs.show', $job->id) }}" class="btn">View Job Posting</a>
            </p>
            
            <p>Thank you for using our platform!</p>
            
            <p>Best regards,<br>The Job & Offer Portal Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Job & Offer Portal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>