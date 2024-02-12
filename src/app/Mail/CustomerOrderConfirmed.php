<?php

namespace App\Mail;

use App\Models\OrderedProduct;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopSales;
use App\Models\SocialMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerOrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $product;

    /**
     * Create a new message instance.
     */
    public function __construct(ShopOrders $order)
    {
        $this->order = $order;
        $prd = OrderedProduct::where('order_id', $this->order->order_id)->first();
        $this->product = ShopProducts::where('id', $prd->product_id)->first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.users.confirmation',
            with: [
                'order_id' => $this->order->order_id,
                'price' => number_format($this->product->price),
                'product' => $this->product,
                'first_name' => $this->order->first_name,
                'last_name' => $this->order->last_name,
                'email' => $this->order->email,
                'discount' => 0,
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
}
