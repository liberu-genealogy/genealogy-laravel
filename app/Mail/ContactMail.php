<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * The message sent by the public contact form.
 *
 * This class did not exist: ContactController imported it and called
 * `new ContactMail(...)`, so every submission since the form shipped threw
 * `Class "App\Mail\ContactMail" not found` and returned a 500.
 *
 * @param  array{name: string, email: string, message: string}  $data
 */
class ContactMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact form: '.$this->data['name'],
            // The sender is the site itself, not the visitor: putting an
            // unverified address in From gets the mail spam-filtered or
            // rejected by SPF/DMARC. Reply-To is where the human goes.
            replyTo: [new Address($this->data['email'], $this->data['name'])],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact');
    }
}
