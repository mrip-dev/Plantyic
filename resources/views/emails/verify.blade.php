<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="margin:0;padding:0;background:#f7f7f7;font-family:Arial,sans-serif;color:#222;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f7f7f7;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#1f6feb;padding:20px 24px;color:#ffffff;font-size:20px;font-weight:700;">
                            Email Verification
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;line-height:1.6;font-size:15px;">
                            <p style="margin:0 0 12px;">Hi {{ $user->name ?? 'there' }},</p>
                            <p style="margin:0 0 12px;">Please use the OTP below to verify your email address:</p>
                            <p style="margin:18px 0;font-size:30px;font-weight:700;letter-spacing:6px;text-align:center;color:#1f6feb;">{{ $otp }}</p>
                            <p style="margin:0 0 12px;">This OTP will expire in <strong>10 minutes</strong>.</p>
                            <p style="margin:0 0 12px;">If you did not request this verification, please ignore this email.</p>
                            <p style="margin:0;">Thanks,<br>The Plantyic Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
