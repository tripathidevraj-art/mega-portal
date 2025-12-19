<!DOCTYPE html>
<html>
<head>
    <title>Job Rejected</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 5px; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        .job-details { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Job Posting Rejected</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $job->user->full_name }},</p>
            
            <p>We regret to inform you that your job posting has been rejected by our admin team.</p>
            
            <div class="job-details">
                <h3>{{ $job->job_title }}</h3>
                <p><strong>Reason for Rejection:</strong> {{ $reason }}</p>
                <p><strong>Posted on:</strong> {{ $job->created_at->format('F d, Y') }}</p>
            </div>
            
            <p>You can review the job posting and make necessary changes before resubmitting for approval.</p>
            
            <p>If you have any questions, please contact our support team.</p>
            
            <p>Best regards,<br>The Job & Offer Portal Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Job & Offer Portal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>