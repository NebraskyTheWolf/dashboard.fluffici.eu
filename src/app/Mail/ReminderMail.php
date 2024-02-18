<?php

namespace App\Mail;

use App\Models\Events;
use App\Models\EventsInteresteds;
use App\Models\ShopOrders;
use App\Models\SocialMedia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Orchid\Platform\Models\User;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Events $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $currentDate = Carbon::parse($this->event->begin);
        $day = $currentDate->day;
        $month = $currentDate->month;
        $time = $currentDate->hour . ':' . $currentDate->minute;

        return new Content(
            view: 'emails.admin.reminder',
            with: [
                'month' => $month,
                'day' => $day,
                'time' => $time,
                'dayFull' => $currentDate->dayName,
                'monthFull' => $currentDate->monthName,
                'eventName' => $this->event->name,
                'interested' => $this->getPeoples(),
                'orders' => count(ShopOrders::all()),
                'name' => $this->user->name,
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

    public function getPeoples(): int
    {
        $peoples = EventsInteresteds::where('event_id', $this->event->event_id);
        if ($peoples->exists()) {
            return $peoples->first()->count();
        }

        return 0;
    }
}
