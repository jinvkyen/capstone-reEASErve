<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UserReservationReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $convertedEndTime;
    public $convertedStartTime;
    public $statusId; // Store status ID
    public $statusState; // Store status state

    public function __construct($user, $convertedEndTime, $statusId, $convertedStartTime)
    {
        $this->user = $user;
        $this->convertedEndTime = $convertedEndTime;
        $this->convertedStartTime = $convertedStartTime;
        $this->statusId = $statusId;

        // Fetch the status state from the database
        $status = DB::table('reservation_status')->where('status_id', $statusId)->first();
        $this->statusState = $status ? $status->status_state : 'Unknown'; // Default value if status state is missing
    }


    public function build()
    {
        return $this->view('mails.user_reservation_reminder')
            ->from('reeaserve@gmail.com', 'ReEaserve')
            ->subject('Reservation Update')
            ->with([
                'statusId' => $this->statusId,
                'statusState' => $this->statusState,
                'convertedEndTime' => $this->convertedEndTime,
                'convertedStartTime' => $this->convertedStartTime,
            ]);
    }
}
