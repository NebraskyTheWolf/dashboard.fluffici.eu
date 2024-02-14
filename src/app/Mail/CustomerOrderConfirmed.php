<?php

namespace App\Mail;

use App\Models\OrderCarrier;
use App\Models\OrderedProduct;
use App\Models\OrderIdentifiers;
use App\Models\ProductTax;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopSales;
use App\Models\SocialMedia;
use App\Models\TaxGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     * @var ShopOrders
     */

    public $order;

    /**
     * An instance of the ShopProducts class representing the ordered product.
     *
     * @var ShopProducts
     */

    public $product;

    /**
     * An instance of the OrderIdentifiers class containing information about the order.
     *
     * @var OrderIdentifiers
     */
    public $publicData;

    /**
     * A collection representing the tax groups associated with the product,
     * or an empty array if there are no tax groups associated with the product.
     *
     * @var
     */
    public $productTax;

    /**
     * The constructor uses dependency injection to receive instances of the ShopOrders and OrderIdentifiers classes.
     * It assigns these instances to the order and publicData properties respectively,
     * and calls the fetchOrderedProduct and fetchProductTax methods to populate the product and productTax properties.
     *
     * @param ShopOrders $order
     * @param OrderIdentifiers $orderIdentifiers
     */
    public function __construct(ShopOrders $order, OrderIdentifiers $orderIdentifiers)
    {
        $this->order = $order;
        $this->fetchOrderedProduct();
        $this->publicData = $orderIdentifiers->fetchOrder($this->order->order_id);
        $this->fetchProductTax();
    }

    /**
     * This method retrieves ordered products from the database based on the order id. Then,
     * it retrieves the corresponding product details from the shop products and assigns the product details to product property.
     *
     * @return void
     */

    protected function fetchOrderedProduct()
    {
        $orderedProduct = OrderedProduct::where('order_id', $this->order->order_id)->first();
        $this->product = ShopProducts::where('id', $orderedProduct->product_id)->first();
    }

    /**
     * This method looks for a product tax entry for the product, and if it exists,
     * it retrieves the corresponding tax group and assigns it to productTax property.
     * If there isn't one, it assigns an empty array to productTax.
     *
     * @return void
     */
    protected function fetchProductTax()
    {
        $tax = ProductTax::where('product_id', $this->product->id);
        if ($tax->exists()) {
            $this->productTax = TaxGroup::where('id', $tax->tax_id)->paginate();
        } else {
            $this->productTax = array();
        }
    }

    protected function fetchOrderCarrier(): int
    {
        $carrier = OrderCarrier::where('order_id', $this->order->order_id);
        if ($carrier->exists()) {
            $data = $carrier->first();
            return $data->price;
        }

        return 0;
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
            'product' => $this->product,
            'productTax' => $this->productTax,
            'publicData' => $this->publicData,
            'carrierFees' => $this->fetchOrderCarrier(),
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
