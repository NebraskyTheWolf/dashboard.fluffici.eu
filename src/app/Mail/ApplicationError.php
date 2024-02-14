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
     * This method is the constructor of the class and is called when a new instance
     * of the class is created. It initializes the class properties with the given values.
     *
     * @param mixed $class The name of the class being constructed.
     * @param mixed $contents The contents of the object being constructed.
     * @param mixed $line The line number of the code being constructed.
     * @param mixed $code The code being constructed.
     *
     * @return void
     */
    public function __construct($class, $contents, $line, $code)
    {
        $this->className = $class;
        $this->contents = $contents;
        $this->line = $line;
        $this->code = $code;
    }

    /**
     * Get the content for the email message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.developer.crash',
            with: [
                'currentDate' => $this->getCurrentDate(),
                'className' => $this->className,
                'contents' => $this->contents,
                'line' => $this->line,
                'code' => $this->code,
                'currentService' => $this->getCurrentService(),
                'socials' => $this->getSocialMedia()
            ]
        );
    }

    /**
     * Get the current date and time.
     *
     * @return \Carbon\Carbon
     */
    private function getCurrentDate()
    {
        return Carbon::now();
    }

    /**
     * Get the current service URL.
     *
     * @return string|null The current service URL or null if not set.
     */
    private function getCurrentService()
    {
        return env('PUBLIC_URL');
    }

    /**
     * Retrieve the social media platforms.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSocialMedia()
    {
        return SocialMedia::all();
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
