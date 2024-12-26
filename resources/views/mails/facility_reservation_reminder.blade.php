<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Reminder from ReEASErve</title>
</head>

<body style="font-family: Montserrat, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f2fafc;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f2fafc">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="600" style="margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" align="center">
                    <tr>
                        <td style="padding: 40px;">
                            <h1 style="color: #2c3e50; margin-top: 0; margin-bottom: 20px; font-size: 28px;">Reminder from ReEASErve</h1>

                            <p style="font-size: 16px; color: #333; text-transform: capitalize;">
                                Dear {{ $user->first_name }} {{ $user->last_name }},
                            </p>

                            <div style="background-color: #e9f7fe; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 16px; color: #3498db;">
                                    @if ($statusId == 5)
                                    This is a friendly reminder that your Reserved Facility must vacate by <strong>{{ $convertedEndTime }}</strong>.
                                    @elseif ($statusId == 3)
                                    Your reservation has been {{ $statusState }}. Please ensure to utilize the facility by <strong>{{ $convertedStartTime }}</strong>.
                                    @elseif ($statusId == 4)
                                    Your reservation has been {{ $statusState }}. If you have any questions, please visit or contact us.
                                    @elseif ($statusId == 8)
                                    Your reservation has been {{ $statusState }}. If you have any questions, please visit or contact us.
                                    @else
                                    Your reservation status is Currently Unknown. Please visit or contact support for more information.
                                    @endif
                                </p>
                            </div>

                            <p style="font-size: 16px; color: #333;">
                                @if ($statusId == 5)
                                Please ensure that you vacate the facility on time.
                                @endif
                            </p>

                            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                                <p style="font-size: 14px; color: #555; margin-bottom: 5px;">
                                    Thank you,
                                </p>
                                <p style="font-size: 16px; color: #2c3e50; font-weight: bold; margin-top: 0;">
                                    ReEASErve Team
                                </p>
                            </div>

                            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
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