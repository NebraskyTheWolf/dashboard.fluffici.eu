<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationError extends Mailable
{
    use Queueable, SerializesModels;

    public $class;
    public $message;
    public $line;
    public $code;


    /**
     * Create a new message instance.
     */
    public function __construct(string $class, string $message, int $line, string $code)
    {
        $this->class = $class;
        $this->message = $message;
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
                'currentDate' => Carbon::now()->diffForHumans(),
                'class' => $this->class,
                'message' => $this->message,
                'line' => $this->line,
                'code' => $this->code
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
