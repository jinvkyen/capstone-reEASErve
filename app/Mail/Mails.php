<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Mails extends Mailable
{
    use Queueable, SerializesModels;

    public $get_user_email;
    public $validToken;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($get_user_email, $validToken, $user)
    {
        $this->get_user_email = $get_user_email;
        $this->validToken = $validToken;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->view('mails.verification_token')
            ->from('reeaserve@gmail.com', 'ReEaserve')
            ->subject('Verification Code');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
