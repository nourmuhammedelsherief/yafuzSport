<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmCode extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;


    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from(settings()['email'], $this->data['messagesTitle'] )
            ->view('emails.confirm_code')
            ->subject($this->data['mailSubject'])
            ->with([
                'headingTitle' => $this->data['headingTitle'],
                'code' => $this->data['code']
            ]);
    }
}
