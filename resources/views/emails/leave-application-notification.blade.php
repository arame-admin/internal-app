<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leave Application Notification</title>
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
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #e0e0e0;
        }
        .details {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            color: #333;
        }
        .footer {
            background: #333;
            color: white;
            padding: 15px;
            border-radius: 0 0 8px 8px;
            text-align: center;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Leave Application Notification</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $reportingManager->name }},</p>
        
        <p>A new leave application has been submitted by <strong>{{ $applicant->name }}</strong> and requires your approval.</p>
        
        <div class="details">
            <div class="detail-row">
                <span class="label">Employee Name:</span>
                <span class="value">{{ $applicant->name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Employee Email:</span>
                <span class="value">{{ $applicant->email }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Department:</span>
                <span class="value">{{ $applicant->department?->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Leave Type:</span>
                <span class="value">{{ ucwords(str_replace('_', ' ', $applyLeave->leave_type)) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Start Date:</span>
                <span class="value">{{ $applyLeave->start_date->format('d M, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">End Date:</span>
                <span class="value">{{ $applyLeave->end_date->format('d M, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Total Days:</span>
                <span class="value">{{ $applyLeave->total_days }} day(s)</span>
            </div>
            <div class="detail-row">
                <span class="label">Reason:</span>
                <span class="value">{{ $applyLeave->reason ?? 'Not provided' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Applied On:</span>
                <span class="value">{{ $applyLeave->created_at->format('d M, Y h:i A') }}</span>
            </div>
        </div>
        
        <p>Please login to the HR system to review and approve/reject this leave application.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('manager.leaves.approve') }}" class="btn">View Leave Requests</a>
        </div>
    </div>
    
    <div class="footer">
        <p>This is an automated notification from AraMeGlobal HRMS. Please do not reply to this email.</p>
    </div>
</body>
</html>
