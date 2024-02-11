<?php

namespace App\Mail;

use App\Models\Pages;
use App\Models\ShopOrders;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
                'percentageOrder' => $this->getPercent(ShopOrders::where('status', 'COMPLETED')->where('status', 'DELIVERED')->count()),
                'orderCount' => ShopOrders::where('status', 'COMPLETED')->where('status', 'DELIVERED')->count(),
                'percentageOverdue' => $this->getPercent(ShopOrders::where('status', 'UNPAID')->count()),

                'delivered' => $this->getPercent(ShopOrders::where('status', 'DELIVERED')->count()),
                'shipping' => $this->getPercent(ShopOrders::where('status', 'SHIPPED')->count()),
                'cancelled' => $this->getPercent(ShopOrders::where('status', 'CANCELLED')->count()),
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

    public function getPercent($value)
    {
        if ($value >= 100) {
            return 100;
        }

        return $value;
    }
}
