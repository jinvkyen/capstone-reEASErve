<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - ReEASErve</title>
</head>
<body style="font-family: 'Montserrat', sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f2fafc;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f2fafc">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="600" style="margin: 40px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);" align="center">
                    <tr>
                        <td style="padding: 40px;">
                            <!-- <img src="{{ asset('storage/assets/logo.png') }}" alt="ReEASErve Logo" style="display: block; margin: 0 auto 20px; max-width: 200px;"> -->
                            
                            <h1 style="color: #2c3e50; margin-bottom: 20px; font-size: 28px; text-align: center;">Password Reset Request</h1>
                            
                            <h2 style="color: #555; text-align: left; text-transform: capitalize;">
                                Hello {{ $user->first_name }},
                            </h2>
                            
                            <p style="font-size: 16px; color: #555; text-align: center;">
                                We received a request to reset your password. Use the code below to complete the process:
                            </p>
                            
                            <div style="background-color: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; margin: 30px 0; text-align: center;">
                                <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #2c3e50;">{{ $validToken }}</span>
                            </div>
                            
                            <div style="background-color: #e9f7fe; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 14px; color: #2980b9;">
                                    <strong>IMPORTANT:</strong> This code will expire in 30 minutes for security reasons. If you didn't request this password reset, please ignore this email or contact support if you have concerns.
                                </p>
                            </div>
                            
                            <p style="font-size: 16px; color: #555; text-align: center;">
                                Need help? <a href="#" style="color: #3498db; text-decoration: none;">Contact the system administrator.</a>
                            </p>
                            
                            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
                                <p style="font-size: 14px; color: #777; margin-bottom: 5px;">
                                    Thank you for using ReEASErve!
                                </p>
                                <p style="font-size: 12px; color: #999;">
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