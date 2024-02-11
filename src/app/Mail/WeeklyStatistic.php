<?php

namespace App\Mail;

use App\Models\Pages;
use App\Models\ShopOrders;
use App\Models\SocialMedia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyStatistic extends Mailable
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
            subject: 'Weekly Statistic',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.statistics',
            with: [
                'range'=> Carbon::now()->diffForHumans(),
                'percentage' => $this->getPercent(Pages::whereMonth('created_at', Carbon::now())->sum('visits')),
                'vists' => Pages::whereMonth('created_at', Carbon::now())->sum('visits'),
                'vistsPrevious' => Pages::all()->sum('visits'),
                'percentageOrder' => $this->getPercent(count(ShopOrders::paginate())),
                'orderCount' => count(ShopOrders::all()),
                'percentageOverdue' => $this->getPercent(count(ShopOrders::where('status', 'UNPAID')->paginate())),

                'delivered' => $this->getPercent(count(ShopOrders::where('status', 'DELIVERED')->paginate())),
                'shipping' => $this->getPercent(count(ShopOrders::where('status', 'SHIPPED')->paginate())),
                'cancelled' => $this->getPercent(count(ShopOrders::where('status', 'CANCELLED')->paginate())),
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

    public function getPercent($value): int
    {
        $val = intval($value);

        if ($val >= 100) {
            return 100;
        }

        if ($val <= 0 || $val == null) {
            return 0;
        }

        return intval($val);
    }
}
