<?php

namespace App\Orchid\Screens\Shop;

use App\Events\OrderUpdateEvent;
use App\Events\UpdateAudit;
use App\Models\OrderCarrier;
use App\Models\OrderedProduct;
use App\Models\OrderPayment;
use App\Models\OrderSales;
use App\Models\ShopCarriers;
use App\Models\ShopCountries;
use App\Models\ShopOrders;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ShopOrderEdit extends Screen
{

    public $order;
    public $orderPayment;
    public $orderProducts;
    public $orderCarrier;
    public $orderSales;
    public $lastHistory;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ShopOrders $order): iterable
    {
        return [
            'order' => $order,

            'orderPayment' => OrderPayment::where('order_id', $order->order_id)->orderBy('created_at', 'desc')->paginate(),
            'lastHistory' => OrderPayment::where('order_id', $order->order_id)->orderBy('created_at', 'desc')->paginate(),
            'orderProducts' => OrderedProduct::where('order_id', $order->order_id)->first(),
            'orderCarrier' => OrderCarrier::where('order_id', $order->order_id)->first(),
            'orderSales' => OrderSales::where('order_id', $order->order_id)->first(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->order->first_name . ' order';
    }


    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Set as completed')
                ->icon('bs.check2-square')
                ->type(Color::SUCCESS)
                ->confirm(__('common.modal.order.type',  ['type' => 'completed']))
                ->method('completed'),
            Button::make('Set as delivered')
                ->icon('bs.envelope-fill')
                ->type(Color::SUCCESS)
                ->confirm(__('common.modal.order.type',  ['type' => 'delivered']))
                ->method('delivered'),
            Button::make('Issue Refund')
                ->icon('bs.slash-circle')
                ->type(Color::WARNING)
                ->confirm(__('common.modal.order.refund'))
                ->method('issueRefund'),
            Button::make('Set as archived')
                ->icon('bs.archive')
                ->type(Color::PRIMARY)
                ->confirm(__('common.modal.order.type',  ['type' => 'archived']))
                ->method('archived'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::tabs([
                'Order Information' => [
                    Layout::legend('order', [
                        Sight::make('Full Name')
                            ->render(fn() => $this->order->first_name . ' ' . $this->order->last_name),
                        Sight::make('first_address'),
                        Sight::make('second_address'),
                        Sight::make('postal_code'),
                        Sight::make('country')
                            ->render(fn() => $this->getCountry()),
                        Sight::make('email'),
                        Sight::make('phone_number'),
                        Sight::make('status')
                            ->render(fn() => $this->orderStatus())
                    ])->title('Contact & Address'),
                    Layout::legend('orderCarrier', [
                        Sight::make('Name')
                            ->render(fn() => $this->getOrderCarrier()->carrierName),
                        Sight::make('Delay')
                            ->render(fn() => $this->getOrderCarrier()->carrierDelay),
                        Sight::make('Price')
                            ->render(fn() => $this->getOrderCarrier()->carrierPrice . ' Kc'),
                    ])->title('Carrier')
                ],
                'Payment' => [
                    \App\Orchid\Layouts\Shop\OrderPayment::class
                ],
                'Ordered Products' => [
                    Layout::legend('orderProducts', [
                        Sight::make('product_name', 'Name')
                            ->render(fn() => $this->orderProducts->product_name),
                        Sight::make('quantity', 'Quantity')
                            ->render(fn() => $this->orderProducts->quantity),
                        Sight::make('price', 'Price')
                            ->render(fn() => $this->orderProducts->price),
                    ])->title('Products')
                ]
            ])->activeTab('Order Information')
        ];
    }

    private function orderStatus(): string
    {
        if ($this->order->status == "PROCESSING") {
            return '<a class="ui blue label">'.__('orders.table.status.processing').' <i class="loading cog icon"></i></a>';
        } else if ($this->order->status == "CANCELLED") {
            return '<a class="ui red label">'.__('orders.table.status.cancelled').'</a>';
        } else if ($this->order->status == "REFUNDED") {
            return '<div><a class="ui orange label">'.__('orders.table.status.refunded').'</a></div>';
        } else if ($this->order->status == "DISPUTED") {
            return '<a class="ui red label">'.__('orders.table.status.disputed').'</a>';
        } else if ($this->order->status == "DELIVERED") {
            return '<a class="ui green label">'.__('orders.table.status.delivered').'</a>';
        } else if ($this->order->status == "ARCHIVED") {
            return '<a class="ui brown label">'.__('orders.table.status.archived').'</a>';
        } else if ($this->order->status == "COMPLETED") {
            return '<div><a class="ui green label">'.__('orders.table.status.completed').'</a></div>';
        } else if ($this->order->status == "OUTING") {
            return '<a class="ui blue label">Payment at Outing <i class="loading cog icon"></i></a>';
        }
        return '<a class="ui purple label">'. $this->order->status . '</a>';
    }

    private function getCountry(): string
    {
        return ShopCountries::where('iso_code', $this->order->country)->firstOrFail()->country_name;
    }

    public function completed()
    {
        ShopOrders::updateOrCreate(
            [ 'order_id' => $this->order->order_id ],
            [
                'status' => 'COMPLETED'
            ]
        );

        event(new OrderUpdateEvent($this->order, 'completed'));
        event(new UpdateAudit('orders', 'Set as Completed ' . $this->order->first_name, Auth::user()->name));

        return redirect()->route('platform.shop.orders');
    }

    public function archived()
    {
        ShopOrders::updateOrCreate(
            [ 'order_id' => $this->order->order_id ],
            [
                'status' => 'ARCHIVED'
            ]
        );

        event(new UpdateAudit('orders', 'Set as Archived ' . $this->order->first_name, Auth::user()->name));

        return redirect()->route('platform.shop.orders');
    }

    public function delivered()
    {
        ShopOrders::updateOrCreate(
            [ 'order_id' => $this->order->order_id ],
            [
                'status' => 'DELIVERED'
            ]
        );

        event(new OrderUpdateEvent($this->order, 'delivered'));
        event(new UpdateAudit('orders', 'Set as Delivered ' . $this->order->first_name, Auth::user()->name));

        return redirect()->route('platform.shop.orders');
    }

    public function issueRefund()
    {
        ShopOrders::updateOrCreate(
            [ 'order_id' => $this->order->order_id ],
            [
                'status' => 'REFUNDED'
            ]
        );

        $payment = $this->getOrderPayment();

        $refund = new OrderPayment();
        $refund->order_id = $this->order->order_id;
        $refund->status = 'REFUNDED';
        $refund->transaction_id = $payment->transaction_id;
        $refund->provider = $payment->provider;
        $refund->price = $payment->price;
        $refund->save();

        Toast::success("You refunded " . $this->order->first_name . ' of ' .  $refund->price . ' Kc');

        event(new OrderUpdateEvent($this->order, 'refund'));
        event(new UpdateAudit('orders', 'Refunded ' . $this->order->first_name, Auth::user()->name));

        return redirect()->route('platform.shop.orders');
    }

    private function getOrderPayment(): OrderPayment
    {
        return OrderPayment::where('order_id', $this->order->order_id)->firstOrFail();
    }

    private function getOrderCarrier(): ShopCarriers
    {
        return ShopCarriers::where('slug', $this->orderCarrier->carrier_name)->first();
    }

}
