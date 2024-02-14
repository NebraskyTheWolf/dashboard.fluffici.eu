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
     * Get the content for the statistics email.
     *
     * @return Content The content for the statistics email.
     */
    public function content(): Content
    {
        $currentMonth = Carbon::now();
        $range = $currentMonth->diffForHumans();
        $currentMonthVisits = Pages::whereMonth('created_at', $currentMonth)->sum('visits');
        $percentageVisits = $this->getPercent($currentMonthVisits);
        $totalVisits = Pages::all()->sum('visits');
        $orderCount = count(ShopOrders::all());
        $percentageOrder = $this->getPercent($orderCount);
        $percentageOverdue = $this->getPercent(count(ShopOrders::where('status', 'UNPAID')->paginate()));
        $delivered = $this->getPercent(count(ShopOrders::where('status', 'DELIVERED')->paginate()));
        $shipping = $this->getPercent(count(ShopOrders::where('status', 'SHIPPED')->paginate()));
        $cancelled = $this->getPercent(count(ShopOrders::where('status', 'CANCELLED')->paginate()));
        $socials = SocialMedia::all();

        return new Content(
            view: 'emails.admin.statistics',
            with: [
                'range' => $range,
                'percentage' => $percentageVisits,
                'vists' => $currentMonthVisits,
                'vistsPrevious' => $totalVisits,
                'percentageOrder' => $percentageOrder,
                'orderCount' => $orderCount,
                'percentageOverdue' => $percentageOverdue,
                'delivered' => $delivered,
                'shipping' => $shipping,
                'cancelled' => $cancelled,
                'socials' => $socials
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

        if ($val <= 0) {
            return 0;
        } else if ($val >= 100) {
            return 100;
        } else {
            return $val;
        }
    }
}
