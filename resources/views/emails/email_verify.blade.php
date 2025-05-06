<!-- resources/views/emails/verify_email.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <h2>Hello {{ $user->username }},</h2>
    
    <p>Thank you for registering with our application. Please click the button below to verify your email address:</p>
    
    <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
    
    <p>If you did not create an account, no further action is required.</p>
    
    <p>If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>
    
    <p>{{ $verificationUrl }}</p>
    
    <div class="footer">
        <p>If you have any questions, feel free to contact our support team.</p>
    </div>
</body>
</html>