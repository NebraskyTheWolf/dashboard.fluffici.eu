<?php

namespace App\Mail;

use App\Models\SocialMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Orchid\Platform\Models\User;

class UserOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your authentication code',
            tags: [
                "fluffici"
            ]
        );
    }

    /**
     * Get the content for the email message.
     *
     * @return Content The content for the email message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.mfa',
            with: $this->getContentData()
        );
    }

    /**
     * Get the content data for the message.
     *
     * @return array An array containing the user, otp token, and a list of all social media.
     */
    private function getContentData(): array
    {
        return [
            'user' => $this->user,
            'otpToken' => $this->otp,
            'socials' => SocialMedia::all()
        ];
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
