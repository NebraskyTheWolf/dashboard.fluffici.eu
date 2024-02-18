<?php

namespace App\Mail;

use App\Models\Events;
use App\Models\SocialMedia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Schedules',
        );
    }

    /**
     * Get the content for the email.
     *
     * @return Content The content of the email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.schedules',
            with: [
                'schedules' => $this->getIncomingStartedOrFinishedEvents(),
                'socials' => SocialMedia::all()
            ]
        );
    }

    /**
     * Get Events which are in the status of either 'INCOMING', 'STARTED', or 'FINISHED'.
     *
     */
    private function getIncomingStartedOrFinishedEvents()
    {
        return Events::orderBy('begin', 'desc')
            ->whereIn('status', ['INCOMING', 'STARTED', 'FINISHED'])
            ->whereMonth('begin', Carbon::now())
            ->whereYear('begin', Carbon::now()->year)
            ->get();
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
