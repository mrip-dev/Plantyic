<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Invitation</title>
</head>
<body style="margin: 0; padding: 0; background: #f4f7fb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1f2937;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background: linear-gradient(180deg, #e8f1ff 0%, #f4f7fb 45%, #f4f7fb 100%); padding: 28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="640" style="max-width: 640px; width: 100%; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 35px rgba(16, 24, 40, 0.1);">
                    <tr>
                        <td style="padding: 0;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background: linear-gradient(120deg, #0f4c81 0%, #1f6fb2 100%);">
                                <tr>
                                    <td style="padding: 28px 32px;">
                                        <div style="font-size: 12px; letter-spacing: 1.2px; text-transform: uppercase; color: #c6dcf4; font-weight: 600;">Plantyic</div>
                                        <h1 style="margin: 10px 0 0 0; font-size: 24px; line-height: 1.35; color: #ffffff; font-weight: 700;">You are invited to join {{ $team->name }}</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 32px 10px 32px;">
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.7;">Hi {{ $name }},</p>
                            <p style="margin: 0 0 18px 0; font-size: 15px; line-height: 1.75; color: #374151;">
                                You have been invited to collaborate with the team
                                <strong style="color: #111827;">{{ $team->name }}</strong> on Plantyic.
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.75; color: #374151;">
                                Click the button below to create your account and accept your invitation.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 22px 0;">
                                <tr>
                                    <td align="center" style="border-radius: 10px; background: #1f6fb2;">
                                        <a href="{{ $registrationLink }}" style="display: inline-block; padding: 13px 24px; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; border-radius: 10px;">Create Account & Join Team</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #f7fafc; border: 1px solid #e5e7eb; border-radius: 10px; margin: 0 0 18px 0;">
                                <tr>
                                    <td style="padding: 14px 14px;">
                                        <p style="margin: 0 0 8px 0; font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.8px;">Invitation Link</p>
                                        <p style="margin: 0; font-size: 13px; color: #1f2937; word-break: break-all; line-height: 1.6;">{{ $registrationLink }}</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 6px 0; font-size: 14px; color: #4b5563; line-height: 1.6;">
                                This invitation will expire on
                                <strong style="color: #111827;">{{ $expiresAt->format('M d, Y h:i A') }}</strong>.
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 13px; color: #6b7280; line-height: 1.65;">
                                If you were not expecting this invitation, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 32px 30px 32px;">
                            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 0 0 16px 0;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.7;">
                                Sent by Plantyic Team<br>
                                Please do not reply to this automated email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
