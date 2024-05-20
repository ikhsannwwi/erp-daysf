<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailData['title'] }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
            text-align: center;
        }

        h3 {
            color: #333;
            margin-bottom: 20px;
        }

        h5 {
            color: #333;
            margin-top: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        span {
            display: inline-block;
            width: 120px;
            margin-right: 10px;
        }

        a {
            color: #1E88E5;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 100px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1E88E5;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="{{ array_key_exists('logo_app_admin', $settings) ? img_src($settings['logo_app_admin'], 'settings') : '' }}" alt="App Logo">
        </div>
        <h3>Password Reset</h3>
        <p>Hello {{ $mailData['username'] }},</p>
        <p>We received a request to reset your password. If you did not make this request, please ignore this email.</p>
        <p>To reset your password, click the button below:</p>
        <a class="btn" href="{{ $mailData['resetLink'] }}" target="_blank">Reset Password</a>
        <h5>Or copy and paste the following link into your browser:</h5>
        <p>{{ $mailData['resetLink'] }}</p>
        <ul>
            <li><span>Username:</span> {{ $mailData['username'] }}</li>
            <li><span>Email:</span> {{ $mailData['email'] }}</li>
        </ul>
        <p>If you have any questions, please contact our support team at <a href="mailto:support@yourcompany.com">support@yourcompany.com</a>.</p>
        <p>Thank you,</p>
        <p>The {{ array_key_exists('nama_app_admin', $settings) ? $settings['nama_app_admin'] : '' }} Team</p>
    </div>
</body>

</html>
