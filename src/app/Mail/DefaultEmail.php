<?php

namespace App\Mail;

use App\Models\SocialMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DefaultEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $messageTitle;
    protected $messageContent;


    /**
     * Constructor method for creating a new object of this class.
     *
     * @param string $title The title of the message.
     * @param string $from The sender's name or email address.
     * @param string $content The content of the message.
     *
     * @return void
     */
    public function __construct(string $title, string $from, string $content)
    {
        $this->messageTitle = $title;
        $this->messageContent = $content;
    }

    /**
     * Create an envelope for the message.
     *
     * @return Envelope The newly created envelope instance.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->messageTitle
        );
    }

    /**
     * Get the email content.
     *
     * @return Content The email content instance.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.default',
            with: [
                'content' => $this->messageContent,
                'socials' => SocialMedia::all()
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
