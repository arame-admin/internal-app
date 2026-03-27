<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timesheet Reminder</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        }
        .message p {
            font-size: 16px;
            margin: 10px 0;
        }
        .date-highlight {
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            background: #f5576c;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: 600;
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
        <h1>⏰ Timesheet Reminder</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->first_name ?? $user->name }},</p>
        
        <div class="message">
            <p>This is a friendly reminder that you have not submitted your timesheet for the following date:</p>
            
            <div class="date-highlight">
                <strong>Date:</strong> {{ $missedDate->format('l, d M, Y') }}
            </div>
            
            <p>Please submit your timesheet as soon as possible to ensure accurate record-keeping.</p>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ route('employee.timesheets.apply') }}" class="btn">Submit Timesheet</a>
        </div>
        
        <p style="margin-top: 20px;">If you have already submitted your timesheet, please disregard this reminder.</p>
        
        <p>Best regards,<br>
        <strong>The AraMeGlobal Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated reminder from AraMeGlobal HRMS. Please do not reply to this email.</p>
    </div>
</body>
</html>
