<?php

namespace App\Mail;

use App\Models\SocialMedia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationError extends Mailable
{
    use Queueable, SerializesModels;

    public $className;
    public $contents;
    public $line;
    public $code;


    /**
     * Create a new message instance.
     */
    public function __construct($class, $contents, $line, $code)
    {
        $this->className = $class;
        $this->contents = $contents;
        $this->line = $line;
        $this->code = $code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Error',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.developer.crash',
            with: [
                'currentDate' => Carbon::now(),
                'className' => $this->className,
                'contents' => $this->contents,
                'line' => $this->line,
                'code' => $this->code,
                'currentService' => env('PUBLIC_URL'),
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
        return [

        ];
    }
}
