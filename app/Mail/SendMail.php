<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subj;
    public $url = 'http://localhost:3000';
    public $msg;
    private $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $message, $attachment = null)
    {
        $this->subj = $subject;
        $this->msg = $message;
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.send_mail')
            ->text('emails.send_mail_plain')
            ->subject($this->subj)
//            ->attach(public_path('storage/avatar.png'))
            ->with(['message' => $this]);

        if ($this->attachment) {
            foreach ($this->attachment as $filePath) {
                $email->attach(public_path('storage/'. $filePath));
            }
        }

        return $email;
    }
}
