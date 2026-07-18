<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return $this
     */
    public function build()
    {
        return $this->subject('มีข้อความติดต่อใหม่: ' . $this->contact->title)
            ->view('emails.new-contact');
    }
}
