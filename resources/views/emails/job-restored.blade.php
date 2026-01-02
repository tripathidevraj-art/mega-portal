<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Job Posting Is Now Live Again</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 650px; margin: 0 auto; padding: 20px;">
    <div style="background: #4361ee; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h2 style="color: white; margin: 0;">✅ Job Restored!</h2>
    </div>
    
    <div style="border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 8px 8px; padding: 25px; background: #fff;">
        <p>Hello {{ $job->user->full_name }},</p>
        
        <p>Great news! Your job posting has been reviewed and is now <strong>live again</strong> on our platform:</p>
        
        <div style="background: #f8f9fa; border-left: 4px solid #4361ee; padding: 15px; margin: 20px 0; border-radius: 0 4px 4px 0;">
            <h3 style="margin: 0 0 10px; color: #4361ee;">{{ $job->job_title }}</h3>
            <p style="margin: 5px 0;"><strong>Industry:</strong> {{ $job->industry }}</p>
            <p style="margin: 5px 0;"><strong>Location:</strong> {{ $job->work_location }}</p>
            <p style="margin: 5px 0;"><strong>Job Type:</strong> {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</p>
            <p style="margin: 5px 0;"><strong>Salary:</strong> {{ $job->salary_range }}</p>
        </div>

        <p>Your job is now visible to all users and accepting applications.</p>
        
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ route('jobs.show', $job->id) }}" 
               style="background: #4361ee; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                View Your Job
            </a>
        </p>
        
        <p>Thank you for your patience and contribution to our community!</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="text-align: center; color: #777; font-size: 0.9em;">
            Job & Offer Portal Team<br>
            <a href="{{ url('/') }}" style="color: #4361ee;">{{ config('app.name') }}</a>
        </p>
    </div>
</body>
</html>