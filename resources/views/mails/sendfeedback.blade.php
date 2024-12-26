<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Your Experience - ReEaserve</title>
</head>

<body style="font-family: 'Montserrat', sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f2fafc;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f2fafc">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="600" style="margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);" align="center">
                    <tr>
                        <td style="padding: 0;">
                            <!-- Header -->
                            <div style="background: #eaedf2; padding: 40px 0; text-align: center;">
                                <h1 style="color: #2467d4; margin: 0; font-size: 32px; font-weight: 700;">ReEaserve</h1>
                            </div>

                            <!-- Content -->
                            <div style="padding: 40px;">
                                <h2 style="color: #2c3e50; margin-top: 0; margin-bottom: 20px; font-size: 28px;">How was your experience?</h2>

                                <p style="font-size: 16px; color: #333; text-transform: capitalize;">
                                    Dear {{ $user->first_name }} {{ $user->last_name }},
                                </p>

                                <p style="font-size: 16px; color: #333;">
                                    We hope you found the {{ $itemOrFacility }} useful. Your feedback about this specific {{ $itemOrFacilityType }} is valuable to us and future users.
                                </p>

                                <div style="background-color: #e9f7fe; border-left: 4px solid #3498db; padding: 15px; margin: 20px 0;">
                                    <p style="margin: 0; font-size: 16px; color: #3498db;">
                                        Could you take a moment to rate and review the {{ $itemOrFacility }} from {{ $facilityLocation }} you {{ $actionType }}?
                                    </p>
                                </div>

                                <a href="{{ $feedbackLink }}" style="display: inline-block; padding: 12px 24px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; margin: 20px 0;">
                                    Provide Feedback
                                </a>

                                <p style="font-size: 16px; color: #333;">
                                    Your insights will help us maintain and improve our {{ $itemOrFacilityType }} for everyone in our community.
                                </p>

                                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                                    <p style="font-size: 14px; color: #555; margin-bottom: 5px;">
                                        Thank you for your time,
                                    </p>
                                    <p style="font-size: 16px; color: #2c3e50; font-weight: bold; margin-top: 0;">
                                        The ReEaserve Team
                                    </p>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div style="background-color: #f7f9fc; color: #ffffff; padding: 30px; text-align: center;">

                                <p style="font-size: 12px; color: #a0aec0; margin-top: 20px;">
                                    © {{ date('Y') }} ReEaserve. All rights reserved.
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>