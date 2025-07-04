<!DOCTYPE html>
<html>

<head>
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

        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }

        .footer {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hello, {{ $user->name }}</h2>

        <p>Thank you for registering with CompanyDeals. To complete your registration and access all features, please verify your email address by clicking the button below:</p>

        <a href="{{ $url }}" class="button">Verify Email Address</a>

        <p>If you did not create an account, no further action is required.</p>

        <p>This verification link will expire in 60 minutes.</p>

        <p><b>Please note that your WhatsApp number is currently under review. Our admin team will verify it within the next 12 hours.
                You will receive a confirmation once the verification is complete.

            </b>

        <div class="footer">
            <p>If you're having trouble clicking the button, copy and paste this URL into your web browser:</p>
            <p>{{ $url }}</p>
            <p>Team, CompanyDeals</p>
        </div>
    </div>
</body>

</html>