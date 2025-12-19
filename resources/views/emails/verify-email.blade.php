<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification - Job Portal</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #f8f9fa; padding: 20px; border-radius: 10px;">
        <div style="background: #4361ee; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="margin: 0;">Verify Your Email</h1>
        </div>
        
        <div style="padding: 30px;">
            <p>Hello <strong>{{ $user->full_name }}</strong>,</p>
            
            <p>Thank you for registering with Job & Offer Portal. Please verify your email address by clicking the button below:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 12px 24px; background: #4361ee; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Verify Email Address
                </a>
            </div>
            
            <p>Or copy and paste this link in your browser:</p>
            <p style="background: #f1f1f1; padding: 10px; border-radius: 5px; word-break: break-all;">
                {{ $verificationUrl }}
            </p>
            
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
                <p style="margin: 0;"><strong>Important:</strong> This link will expire in 24 hours.</p>
            </div>
            
            <p>If you didn't create an account, please ignore this email.</p>
            
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