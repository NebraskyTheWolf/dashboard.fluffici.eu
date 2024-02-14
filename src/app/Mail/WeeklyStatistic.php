<?php

namespace App\Mail;

use App\Models\Pages;
use App\Models\ShopOrders;
use App\Models\SocialMedia;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyStatistic extends Mailable
{
    use Queueable, SerializesModels;

    /**
     *
     * Constructs a new instance of the class.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create an envelope for a message.
     *
     * @return Envelope The created envelope object.
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
        $currentDate = Carbon::now();

        $currentMonthVisits = Pages::whereMonth('created_at', $currentDate)->sum('visits');
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
                'range' => $this->getPeriod(),
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
     * Format a given date into a string representing the day in the following format: [Day Name] [Day Number] at [Time].
     *
     * @param Carbon $date The date to format.
     *
     * @return string The formatted day string in the format of [Day Name] [Day Number] at [Time].
     */
    public function formatDay(Carbon $date): string
    {
        return substr($date->dayName, 0, 3) . ' ' . $date->day . ' at ' . $date->format('H:i');
    }

    /**
     * Get the current period in a specific format.
     *
     * @return string The current period in the format "start day, end day".
     */
    public function getPeriod(): string
    {
        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek = $currentDate->copy()->endOfWeek();

        $start = $this->formatDay($startOfWeek);
        $end = $this->formatDay($endOfWeek);

        return $start . ', ' . $end;
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

    /**
     * Get the percentage value of the given number.
     *
     * @param int|float $value The value to calculate the percentage for.
     * @return int  The percentage value as an integer between 0 and 100 (inclusive).
     */
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
