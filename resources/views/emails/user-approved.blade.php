<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Approved - Job Portal</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #f8f9fa; padding: 20px; border-radius: 10px;">
        <div style="background: #28a745; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="margin: 0;">✅ Account Approved!</h1>
        </div>
        
        <div style="padding: 30px;">
            <p>Hello <strong>{{ $user->full_name }}</strong>,</p>
            
            <p>Great news! Your account on <strong>Job & Offer Portal</strong> has been <strong>approved</strong> by our admin team.</p>
          @if($reason)
            <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; color: #155724;">
                <p style="margin: 0;"><strong>Admin Note:</strong> {{ $reason }}</p>
            </div>
            @endif
            <p>You can now log in and start using all features:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/login') }}" style="display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Go to Login
                </a>
            </div>
            
            <p>Thank you for joining our community!</p>
            
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