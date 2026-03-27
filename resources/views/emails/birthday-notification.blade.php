<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Happy Birthday!</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .message {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .message p {
            font-size: 16px;
            margin: 10px 0;
        }
        .cake {
            font-size: 60px;
            margin: 20px 0;
        }
        .footer {
            background: #333;
            color: white;
            padding: 15px;
            border-radius: 0 0 8px 8px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Happy Birthday! 🎉</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->first_name ?? $user->name }},</p>
        
        <div class="message">
            <div class="cake">🎂</div>
            <p><strong>Wishing you a very Happy Birthday!</strong></p>
            <p>May this special day bring you joy, happiness, and all the success you deserve.</p>
            <p>Thank you for being a valued member of our team!</p>
        </div>
        
        <p>Warm regards,<br>
        <strong>The AraMeGlobal Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated birthday greeting from AraMeGlobal HRMS. Please do not reply to this email.</p>
    </div>
</body>
</html>
