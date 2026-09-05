<?php

namespace App\Mail;

use App\Models\YouthApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Sent to the parent right after they submit the enrolment form. */
class YouthApplicationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public YouthApplication $application)
    {
    }

    public function build()
    {
        return $this->subject('Primili smo prijavu za ' . $this->application->child_name)
            ->view('emails.youth_application_confirmation');
    }
}
