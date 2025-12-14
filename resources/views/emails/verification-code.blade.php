<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 40px 20px;
            line-height: 1.6;
            color: #333333;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background-color: #2c3e50;
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .message {
            font-size: 15px;
            color: #555555;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        
        .code-container {
            background-color: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .code-label {
            font-size: 13px;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .verification-code {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: 10px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            padding: 15px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            display: inline-block;
        }
        
        .code-note {
            font-size: 13px;
            color: #888888;
            margin-top: 15px;
        }
        
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }
        
        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        
        .info-box p {
            font-size: 14px;
            color: #856404;
            margin: 0;
            line-height: 1.6;
        }
        
        .info-box strong {
            font-weight: 600;
        }
        
        .footer {
            background-color: #fafafa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        
        .footer .brand {
            font-weight: 600;
            color: #2c3e50;
            font-size: 15px;
            margin-bottom: 10px;
        }
        
        .footer p {
            font-size: 13px;
            color: #888888;
            margin: 5px 0;
        }
        
        .footer-links {
            margin-top: 15px;
        }
        
        .footer-links a {
            color: #2c3e50;
            text-decoration: none;
            margin: 0 10px;
            font-size: 13px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        @media only screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .verification-code {
                font-size: 32px;
                letter-spacing: 8px;
            }
            
            .footer {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1> STAR²S </h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $userName }},
            </div>
            
            <div class="message">
                Thank you for registering with Housing Reservation System. To complete your registration and verify your email address, please use the verification code below.
            </div>
            
            <div class="code-container">
                <div class="code-label">Verification Code</div>
                <div class="verification-code">{{ $verificationCode }}</div>
                <div class="code-note">This code will expire in 24 hours</div>
            </div>
                        
        </div>
        
        <div class="footer">
            <p class="brand">Housing Reservation System</p>
            <p>&copy; {{ date('Y') }} Housing Reservation System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
