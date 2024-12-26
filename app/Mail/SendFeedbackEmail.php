<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFeedbackEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $itemOrFacility;
    public $itemOrFacilityType;
    public $actionType;
    public $feedbackLink;

    public $facilityLocation;

    public function __construct($user, $itemOrFacility, $itemOrFacilityType, $actionType, $feedbackLink, $facilityLocation)
    {
        $this->user = $user;
        $this->itemOrFacility = $itemOrFacility;
        $this->itemOrFacilityType = $itemOrFacilityType;
        $this->actionType = $actionType;
        $this->feedbackLink = $feedbackLink;
        $this->facilityLocation = $facilityLocation;
    }

    public function build()
    {
        return $this->view('mails.sendfeedback')
            ->from('reeaserve@gmail.com', 'ReEaserve')
            ->subject('Share Your Experience! ⭐');
    }
}
