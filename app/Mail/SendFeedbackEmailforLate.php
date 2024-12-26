<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFeedbackEmailforLate extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $itemOrFacility;
    public $itemOrFacilityType;
    public $actionType;
    public $feedbackLink;

    public $convertedTime;

    public function __construct($user, $itemOrFacility, $itemOrFacilityType, $actionType, $feedbackLink, $convertedTime)
    {
        $this->user = $user;
        $this->itemOrFacility = $itemOrFacility;
        $this->itemOrFacilityType = $itemOrFacilityType;
        $this->actionType = $actionType;
        $this->feedbackLink = $feedbackLink;
        $this->convertedTime = $convertedTime;
        
    }

    public function build()
    {
        return $this->view('mails.sendfeedbackforlate')
            ->from('reeaserve@gmail.com', 'ReEaserve')
            ->subject('🚨 Notice:');
    }
}
