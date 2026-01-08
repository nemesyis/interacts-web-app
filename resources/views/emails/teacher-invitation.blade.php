<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0d6efd;
        }
        .credentials-item {
            margin: 10px 0;
        }
        .credentials-label {
            font-weight: bold;
            color: #495057;
        }
        .credentials-value {
            font-family: 'Courier New', monospace;
            background: white;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }
        .button {
            display: inline-block;
            background: #0d6efd;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">Welcome to Interacts!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Teacher Invitation</p>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $full_name }}</strong>,</p>

            <p>You have been invited to join <strong>Interacts</strong> as a teacher. We're excited to have you on board!</p>

            <div class="credentials">
                <h3 style="margin-top: 0; color: #0d6efd;">Your Login Credentials</h3>
                
                <div class="credentials-item">
                    <div class="credentials-label">Username:</div>
                    <div class="credentials-value">{{ $username }}</div>
                </div>

                <div class="credentials-item">
                    <div class="credentials-label">Password:</div>
                    <div class="credentials-value">[You will set this upon accepting the invitation]</div>
                </div>
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong> Your password IS NOT set. You will be required to set it when you first log in.
            </div>

            <center>
                <a href="{{ route('teacher.accept', $invitation_token) }}" class="button" style="color: khaki;">Accept Invitation & Set Password</a>
            </center>

            <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
                <strong>Getting Started:</strong><br>
                1. Click the button above<br>
                2. Set your own password<br>
                3. Start creating classrooms and appointments!
            </p>

            <p>If you have any questions or need assistance, please contact the administrator.</p>

            <p>Best regards,<br>
            <strong>Interacts Team</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Interacts. All rights reserved.</p>
        </div>
    </div>
</body>
</html>