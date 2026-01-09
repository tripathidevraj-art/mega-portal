<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Announcement: {{ $news->title }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #007bff;">📢 New Announcement</h2>
    
    <p>Hello {{ $user->full_name ?? 'User' }},</p>
    
    <p>A new announcement has been published on the Job & Offer Portal:</p>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #007bff;">
        <h3>{{ $news->title }}</h3>
        <p><strong>Published on:</strong> {{ $news->created_at->format('F d, Y') }}</p>
        @if($news->excerpt)
            <p>{{ $news->excerpt }}</p>
        @endif
    </div>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/news/' . $news->id) }}" 
           style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Read Full Announcement
        </a>
    </p>
    
    <p>Stay updated with the latest news and opportunities!</p>
    
    <p>Best regards,<br>Job & Offer Portal Team</p>
</body>
</html>