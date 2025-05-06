<!DOCTYPE html>
<html>

<head>
    <title>Confirm Email Change</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #333;">Hello {{ $user->username }},</h2>

        <p style="color: #555;">You requested to change the email address associated with your account.</p>

        <p style="color: #555;">Please click the button below to confirm this change:</p>

        <a href="{{ $resetUrl }}"
            style="display: inline-block; background-color: #38a169; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Confirm Email Change
        </a>

        <p style="color: #888; margin-top: 20px;">This link will expire in 60 minutes.</p>

        <p style="color: #888;">If you did not request this change, no action is needed and your email will remain the same.</p>

        <p style="color: #333;">Regards,<br>Your Application Team</p>
    </div>
</body>

</html>
