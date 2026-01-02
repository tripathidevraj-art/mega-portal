<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Job Report Submitted</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 650px; margin: 0 auto; padding: 20px;">
    <div style="background: #e01e5a; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h2 style="color: white; margin: 0;">🚨 New Job Report</h2>
    </div>
    
    <div style="border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 8: 8px; padding: 25px; background: #fff;">
        <p>Hello Admin,</p>
        
        <p>A user has reported a job posting. Please review it as soon as possible.</p>
        
        <div style="background: #fff8e1; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 0 4px 4px 0;">
            <h3 style="margin: 0 0 10px; color: #d39e00;">Report Details</h3>
            <p><strong>Job:</strong> {{ $report->job->job_title }}</p>
            <p><strong>Reported By:</strong> {{ $report->reporter->full_name }} ({{ $report->reporter->email }})</p>
            <p><strong>Reason:</strong> {{ \App\Models\JobReport::getReasonLabel($report->reason) }}</p>
            @if($report->details)
                <p><strong>Details:</strong> "{{ $report->details }}"</p>
            @endif
            <p><strong>Reported At:</strong> {{ $report->created_at->format('M d, Y \a\t g:i A') }}</p>
        </div>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.reported-jobs') }}" 
               style="background: #e01e5a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                Review Reports Now
            </a>
        </p>
        
        <p>Thank you for keeping our platform safe!</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="text-align: center; color: #777; font-size: 0.9em;">
            Job & Offer Portal Team
        </p>
    </div>
</body>
</html>