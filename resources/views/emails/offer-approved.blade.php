<!DOCTYPE html>
<html>
<head>
    <title>Offer Approved</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4361ee; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 5px; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #4361ee; color: white; text-decoration: none; border-radius: 5px; }
        .offer-details { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Offer Approved</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $offer->user->full_name }},</p>
            
            <p>We're pleased to inform you that your offer has been approved by our admin team.</p>
            
            <div class="offer-details">
                <h3>{{ $offer->product_name }}</h3>
                <p><strong>Category:</strong> {{ $offer->category }}</p>
                <p><strong>Price:</strong> ${{ number_format($offer->price, 2) }}</p>
                <p><strong>Final Price:</strong> <span class="text-success">${{ number_format($offer->final_price, 2) }}</span></p>
                <p><strong>Expiry Date:</strong> {{ $offer->expiry_date->format('F d, Y') }}</p>
                @if($reason)
                <p><strong>Admin Note:</strong> {{ $reason }}</p>
                @endif
            </div>
            
            <p>Your offer is now visible to all users on our platform.</p>
            
            <p style="text-align: center;">
                <a href="{{ route('offers.show', $offer->id) }}" class="btn">View Offer</a>
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