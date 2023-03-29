<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subj;
    public $url = 'http://localhost:3000';
    public $name;
    public $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->name = $data['to'];
        $this->subj = $data['subject'];
        $this->msg = $data['message'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.testmail')
            ->text('emails.testmail_plain')
            ->subject($this->subj)
            ->attach(public_path('storage/avatar.png'))
            ->with(['message' => $this]);
    }
}
