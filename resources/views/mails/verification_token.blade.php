<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ReEASErve - Account Verification</title>
</head>

<body style="font-family: 'Montserrat', sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f2fafc;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f2fafc">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="600" style="margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);" align="center">
                    <tr>
                        <td style="padding: 0;">
                            <!-- Header -->
                            <div style="background: linear-gradient(109.6deg, rgb(220, 196, 252) 11.2%, rgb(150, 199, 253) 100.2%); padding: 40px 0; text-align: center;">
                                <h1 style="color: #ffffff; margin: 0; font-size: 32px; font-weight: 700;">Welcome to ReEASErve! 💼</h1>
                            </div>

                            <!-- Content -->
                            <div style="padding: 40px;">
                                <h2 style="color: #4a5568; margin-bottom: 20px; font-size: 24px; text-align: left; text-transform: capitalize;">
                                    Hello {{ $user ?? 'System Admin' }},
                                </h2>

                                <div style="background-color: #fef6e4; border-left: 4px solid #f6ad55; padding: 20px; margin-bottom: 30px; border-radius: 8px;">
                                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #d97706; text-align: left;">IMPORTANT NOTE</h3>
                                    <p style="margin: 0; font-size: 16px; color: #92400e; text-align: left;">Your USERNAME is your Student Number or Employee Number.</p>
                                </div>

                                <h2 style="color: #4a5568; margin: 30px 0 15px; font-size: 24px; text-align: center;">Your Account Verification Code</h2>
                                <p style="margin-bottom: 20px; font-size: 16px; text-align: center; color: #718096;">Please use the following code to complete your verification process:</p>

                                <div style="background: linear-gradient(135deg, #e6e9f0 0%, #eef1f5 100%); border-radius: 12px; padding: 30px; font-size: 36px; font-weight: bold; text-align: center; letter-spacing: 12px; margin: 30px 0; color: #4a5568; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                    {{$validToken}}
                                </div>

                                <div style="background-color: #e6fffa; border-left: 4px solid #38b2ac; padding: 20px; margin: 30px 0; border-radius: 8px;">
                                    <p style="margin: 0; font-size: 14px; color: #234e52;">
                                        If you didn't request account verification, please ignore this email or contact support if you have concerns.
                                    </p>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div style="background-color: #2d3748; color: #ffffff; padding: 30px; text-align: center;">
                                <p style="font-size: 14px; margin-bottom: 10px;">
                                    Thank you for choosing ReEASErve!
                                </p>
                                <p style="font-size: 14px; font-weight: bold; margin-bottom: 20px;">
                                    The ReEASErve Team
                                </p>
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