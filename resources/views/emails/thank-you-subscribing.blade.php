<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for subscribing to us</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #faf9f6; color: #1c1917; margin: 0; padding: 40px 20px;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e7e5e4; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="padding: 32px 32px 24px; border-bottom: 1px solid #f5f5f4;">
                <span style="font-size: 24px; font-weight: bold; font-family: Georgia, serif; color: #1c1917; text-decoration: none;">Ruang.</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 32px;">
                <h1 style="font-family: Georgia, serif; font-size: 26px; font-weight: normal; color: #1c1917; margin-top: 0; margin-bottom: 16px; line-height: 1.3;">
                    Thank you for subscribing to us!
                </h1>
                <p style="font-size: 16px; line-height: 1.6; color: #44403c; margin-bottom: 20px;">
                    We are thrilled to welcome you to the Ruang community. You will now receive our curated perspectives, in-depth articles, and fresh ideas directly in your inbox.
                </p>
                <p style="font-size: 15px; line-height: 1.6; color: #78716c; margin-bottom: 28px;">
                    Stay curious and take your time exploring ideas that matter.
                </p>
                <div style="margin-bottom: 28px;">
                    <a href="{{ route('home') }}" style="display: inline-block; background-color: #1c1917; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 9999px; font-size: 14px; font-weight: 500;">
                        Explore Ruang Stories
                    </a>
                </div>
                <hr style="border: none; border-top: 1px solid #f5f5f4; margin: 28px 0 20px;">
                <p style="font-size: 12px; color: #a8a29e; line-height: 1.5; margin: 0;">
                    You received this email because <strong>{{ $email }}</strong> subscribed to Ruang newsletter updates.
                </p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #faf9f6; padding: 16px 32px; text-align: center; border-top: 1px solid #f5f5f4;">
                <p style="font-size: 12px; color: #a8a29e; margin: 0;">
                    © {{ date('Y') }} Ruang Editorial. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
