<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Not Approved - Job Portal</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #f8f9fa; padding: 20px; border-radius: 10px;">
        <div style="background: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="margin: 0;">⚠️ Account Not Approved</h1>
        </div>
        
        <div style="padding: 30px;">
            <p>Hello <strong>{{ $user->full_name }}</strong>,</p>
            
            <p>We regret to inform you that your account request for <strong>Job & Offer Portal</strong> was <strong>not approved</strong>.</p>

            @if($reason)
            <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0; color: #721c24;">
                <p style="margin: 0;"><strong>Reason:</strong> {{ $reason }}</p>
            </div>
            @endif

            <p>If you believe this is a mistake, please contact our support team.</p>
            
            <p>Thank you for your interest.</p>
            
            <p>Best regards,<br>
            <strong>Job & Offer Portal Team</strong></p>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
            <p>This is an automated email. Please do not reply.</p>
            <p>&copy; {{ date('Y') }} Job & Offer Portal</p>
        </div>
    </div>
</body>
</html>