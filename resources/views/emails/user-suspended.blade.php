<!DOCTYPE html>
<html>
<head>
    <title>Account Suspended</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 5px; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        .important { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Suspension Notice</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $user->full_name }},</p>
            
            <div class="important">
                <p><strong>IMPORTANT:</strong> Your account has been suspended.</p>
            </div>
            
            <p><strong>Reason:</strong> {{ $reason }}</p>
            <p><strong>Suspension Duration:</strong> {{ $durationDays }} days</p>
            <p><strong>Suspension End Date:</strong> {{ $user->suspended_until->format('F d, Y h:i A') }}</p>
            
            <p>During the suspension period, you will not be able to:</p>
            <ul>
                <li>Log in to your account</li>
                <li>Post new jobs or offers</li>
                <li>Edit existing content</li>
                <li>Access premium features</li>
            </ul>
            
            <p>If you believe this is a mistake or would like to appeal the suspension, please contact our support team.</p>
            
            <p>Best regards,<br>The Job & Offer Portal Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Job & Offer Portal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>