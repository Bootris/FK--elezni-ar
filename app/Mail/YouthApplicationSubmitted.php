<?php

namespace App\Mail;

use App\Models\YouthApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Sent to the club when a parent submits the enrolment form. */
class YouthApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public YouthApplication $application)
    {
    }

    public function build()
    {
        return $this->subject('Nova prijava za upis — ' . $this->application->child_name)
            ->replyTo($this->application->email, $this->application->parent_name)
            ->view('emails.youth_application');
    }
}
