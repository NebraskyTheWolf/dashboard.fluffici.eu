<?php

namespace App\Mail;

use App\Models\SocialMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Orchid\Platform\Models\User;

class DefaultEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $messageTitle;
    protected $messageContent;
    protected $author;


    /**
     * Constructor method for creating a new object of this class.
     *
     * @param string $title The title of the message.
     * @param string $content The content of the message.
     *
     * @return void
     */
    public function __construct(string $title, string $content, User $author)
    {
        $this->messageTitle = $title;
        $this->messageContent = $content;
        $this->author = $author;
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
                'assignee' => $this->author->name,
                'avatarURL' => $this->fetchUserAvatar(),
                'content' => $this->messageContent,
                'socials' => SocialMedia::all()
            ]
        );
    }

    public function fetchUserAvatar(): string
    {
        if ($this->author->avatar == 1) {
            return 'https://autumn.fluffici.eu/avatars/' . $this->author->avatar_id . '?width=256&height=256';
        }
        return 'https://ui-avatars.com/api/?name=' . $this->author->name . '&background=0D8ABC&color=fff';
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
