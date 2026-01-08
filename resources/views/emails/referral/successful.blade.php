<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Referral Joined!</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #ffffff; color: #212529;">

    <h2 style="color: #198754;">🎉 New Referral Joined!</h2>

    <p>Hello,</p>

    <p>
        <strong>{{ $referredUser->full_name }}</strong> just joined <strong>{{ config('app.name') }}</strong>
        using your referral link.
    </p>

    <div style="background: #d1e7dd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #198754;">
        <h4 style="margin-top: 0; color: #0f5132;">
            You've earned {{ $points }} points 🎉
        </h4>
        <p style="margin-bottom: 0;">
            Keep sharing your referral link to earn more rewards!
        </p>
    </div>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/profile') }}"
           style="background: #198754; color: #ffffff; padding: 10px 22px; text-decoration: none; border-radius: 5px; display: inline-block;">
            View Your Referral Stats
        </a>
    </p>

    <p>
        Thanks,<br>
        <strong>{{ config('app.name') }}</strong>
    </p>

</body>
</html>
