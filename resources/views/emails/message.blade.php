<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $messageContent->subject }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px; text-align: center;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #1e293b; border-radius: 4px 4px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 20px; font-weight: 500; letter-spacing: 0.5px;">
                                {{ config('app.name') }}
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 24px; color: #0f172a; font-size: 18px; font-weight: 600; line-height: 1.4;">
                                {{ $messageContent->subject }}
                            </h2>
                            
                            <div style="margin: 0 0 32px; color: #475569; font-size: 15px; line-height: 1.6;">
                                {!! nl2br(e($messageContent->content)) !!}
                            </div>

                            <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                                <p style="margin: 0; color: #64748b; font-size: 14px; line-height: 1.5;">
                                    Best regards,<br>
                                    <span style="color: #0f172a; font-weight: 500;">{{ config('app.name') }}</span>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f8fafc; border-radius: 0 0 4px 4px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.5;">
                                This is an automated message. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html> 