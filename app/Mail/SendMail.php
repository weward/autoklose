<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class SendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $messageBody;

    protected $messageSubject;

    protected $toEmailAddress;

    /**
     * Create a new message instance.
     */
    public function __construct($messageBody, $messageSubject, $toEmailAddress)
    {
        $this->messageBody = $messageBody;
        $this->messageSubject = $messageSubject;
        $this->toEmailAddress = $toEmailAddress;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->toEmailAddress, 'Sender'),
            subject: $this->messageSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.send-mail',
            with: [
                'messageBody' => $this->messageBody,
            ]
        );
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
