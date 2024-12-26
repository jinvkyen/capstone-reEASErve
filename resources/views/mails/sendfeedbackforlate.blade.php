<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Important Notice - Overdue Item - ReEASErve</title>
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
                                <h1 style="color: #2467d4; margin: 0; font-size: 32px; font-weight: 700;">ReEASErve</h1>
                            </div>

                            <!-- Content -->
                            <div style="padding: 40px;">
                                <h2 style="color: #e74c3c; margin-top: 0; margin-bottom: 20px; font-size: 28px; text-align: center;">Your Borrowed Resource Was Returned Late</h2>

                                <p style="font-size: 16px; color: #333; text-transform: capitalize;">
                                    Dear {{ $user->first_name }} {{ $user->last_name }},
                                </p>

                                <div style="background-color: #fadbd8; border-left: 4px solid #e74c3c; padding: 15px; margin: 20px 0;">
                                    <p style="margin: 0; font-size: 16px; color: #c0392b;">
                                        Our records indicate that the following item is overdue:
                                    </p>
                                    <p style="margin: 10px 0 0; font-size: 18px; font-weight: bold; color: #c0392b; text-align:center;">
                                        {{ $itemOrFacility }}
                                    </p>
                                </div>

                                <p style="font-size: 16px; color: #333; text-align: justify;">
                                    The due date for this item was <strong>{{ $convertedTime }}</strong>.
                                    As you plan for future borrowing of resources, we kindly remind you to ensure that all borrowed equipment or even facilities are returned on time.
                                    Returning items promptly helps avoid any penalties and ensures availability for other users.
                                </p>

                                <a href="{{ $feedbackLink }}" style="display: inline-block; padding: 12px 24px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; margin: 20px 0;">
                                    Provide Feedback
                                </a>

                                <p style="font-size: 16px; color: #333;">
                                    Your insights will help us maintain and improve our {{ $itemOrFacilityType }} for everyone in our community.
                                </p>

                                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                                    <p style="font-size: 14px; color: #555; margin-bottom: 5px;">
                                        Thank you for your cooperation,
                                    </p>
                                    <p style="font-size: 16px; color: #2c3e50; font-weight: bold; margin-top: 0;">
                                        The ReEASErve Team
                                    </p>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div style="background-color: #f7f9fc; color: #ffffff; padding: 30px; text-align: center;">

                                <p style="font-size: 12px; color: #a0aec0; margin-top: 20px;">
                                    © {{ date('Y') }} ReEASErve. All rights reserved.
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