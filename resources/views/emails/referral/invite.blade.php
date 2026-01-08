<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>You're Invited to Join JobPortal</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #ffffff; color: #212529;">

    <h2 style="color: #0d6efd;">You're Invited to Join JobPortal</h2>

    <p>Hello {{ $recipientName }},</p>

    <p>
        <strong>{{ $sender->full_name }}</strong> has invited you to join <strong>JobPortal</strong>.
        Click the button below to register using their referral link and get started.
    </p>

    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <p style="margin: 0;">
            JobPortal helps you discover job opportunities, apply easily, and connect with employers.
        </p>
    </div>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ $link }}"
           style="background: #0d6efd; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Join Now
        </a>
    </p>

    <p>
        If you did not expect this invitation, you can safely ignore this email.
    </p>

    <p>
        Thanks,<br>
        <strong>JobPortal Team</strong>
    </p>

</body>
</html>
