<?php

namespace App\Mail;

use App\Models\Shop\Customer\Order\OrderIdentifiers;
use App\Models\Shop\Customer\Order\ShopOrders;
use App\Models\Shop\Internal\TaxGroup;
use App\Models\SocialMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerOrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * An instance of the ShopOrders class representing the customer's order.
     *
     * @var \app\Models\Shop\Customer\Order\ShopOrders
     */

    public ShopOrders $order;

    /**
     * The constructor uses dependency injection to receive instances of the ShopOrders and OrderIdentifiers classes.
     * It assigns these instances to the order and publicData properties respectively,
     * and calls the fetchOrderedProduct and fetchProductTax methods to populate the product and productTax properties.
     *
     * @param \app\Models\Shop\Customer\Order\ShopOrders $order
     * @param \app\Models\Shop\Customer\Order\OrderIdentifiers $orderIdentifiers
     */
    public function __construct(ShopOrders $order, OrderIdentifiers $orderIdentifiers)
    {
        $this->order = $order;
    }

    /**
     * This method returns an instance of Content class
     * and configures the view and data that will be used for the email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmed',
        );
    }

    /**
     * This method returns an instance of Content class and configures
     * the view and data that will be used for the email.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.users.confirmation',
            with: $this->buildContentData()
        );
    }

    /**
     * This method builds an array of content data for a specific order.
     * It includes the order details, product details, product tax details, public data,
     * order carrier fees, and social media information.
     *
     * @return array Returns an array of content data for the order.
     */
    private function buildContentData(): array
    {
        return [
            'order' => $this->order,
            'product' => $this->order->orderedProducts(),
            'productTax' => TaxGroup::all(),
            'publicData' => $this->order->identifiers(),
            'carrierFees' => $this->order->carrier(),
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
