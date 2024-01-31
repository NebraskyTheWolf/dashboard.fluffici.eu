<?php

namespace App\Http\Controllers;

use App\Events\OrderUpdateEvent;
use App\Models\OrderCarrier;
use App\Models\OrderedProduct;
use App\Models\OrderPayment;
use App\Models\OrderSales;
use App\Models\ShopCarriers;
use App\Models\ShopCountries;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopSales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ShopController extends Controller {

    public function index(Request $request) {
        $productId = (isset($request->productId)) ? $request->productId : false;

        $product = ShopProducts::where('id', $productId);
        $sale = ShopSales::where('product_id', $productId);

        if ($sale->exists()) {
            $discount = $sale->firstOrFail();

            if ($product->exists()) {
                $data = $product->firstOrFail();
                $finalPrice = 0;

                if (!Carbon::parse($discount->deleted_at)->isPast()) {
                    $finalPrice = $data->price * ($discount->reduction / 100);
                }

                $view = view('shop.checkout')
                    ->with('productId', $data->id)
                    ->with('productName', $data->name)
                    ->with('productPrice', $data->price - $finalPrice)
                    ->with('originalPrice', $data->price)
                    ->with('discounted', $discount->reduction)
                    ->with('productDescription', strip_tags($data->description))
                    ->with('countries', ShopCountries::paginate())
                    ->with('payment', false)
                    ->with('success', false)
                    ->with('failed', false)
                    ->with('carriers', ShopCarriers::paginate());

                if ($data->image_path !== null) {
                    $view->with('productURL', 'https://autumn.rsiniya.uk/attachments/' . $data->image_path);
                } else {
                    $view->with('productURL', 'https://autumn.rsiniya.uk/attachments/E1dC5nCVCCSnYwTmUTS7JMYAZiwOeb1xa8XCFPmu4j');
                }

                return $view;
            } else {
                return redirect('https://shop.fluffici.eu');
            }
        } else {
            if ($product->exists()) {
                $data = $product->firstOrFail();
                $view = view('shop.checkout')
                    ->with('productId', $data->id)
                    ->with('productName', $data->name)
                    ->with('productPrice', $data->price)
                    ->with('originalPrice', $data->price)
                    ->with('discounted', 0)
                    ->with('productDescription', strip_tags($data->description))
                    ->with('countries', ShopCountries::paginate())
                    ->with('payment', false)
                    ->with('success', false)
                    ->with('failed', false)
                    ->with('carriers', ShopCarriers::paginate());

                if ($data->image_path !== null) {
                    $view->with('productURL', 'https://autumn.rsiniya.uk/attachments/' . $data->image_path);
                } else {
                    $view->with('productURL', 'https://autumn.rsiniya.uk/attachments/E1dC5nCVCCSnYwTmUTS7JMYAZiwOeb1xa8XCFPmu4j');
                }

                return $view;
            } else {
                return redirect('https://shop.fluffici.eu')
                    ->with('toast', 'This product is not available.');
            }
        }
    }

    public function createOrder(Request $request) {

        $orderId = Uuid::uuid4();

        $order = new ShopOrders();
        $order->order_id = $orderId;
        $order->first_name = $request->input('first-name');
        $order->last_name = $request->input('last-name');
        $order->phone_number = $request->input('phone');
        $order->email = $request->input('email');
        $order->first_address = $request->input('address-one');
        $order->second_address = $request->input('address-two');
        $order->postal_code = $request->input('zip-code');
        $order->country = $request->input('country');
        $order->status = 'PROCESSING';
        $order->save();

        $remoteCarrier = ShopCarriers::where('slug', $request->input('carrier'));
        $carrierPrice = 0;
        if ($remoteCarrier->exists()) {
            $prdCarrier = $remoteCarrier->firstOrFail();
            $carrierPrice = $prdCarrier->carrierPrice;

            $carrier = new OrderCarrier();
            $carrier->order_id = $orderId;
            $carrier->carrier_name = $request->input('carrier');
            $carrier->price = $carrierPrice;
            $carrier->save();
        }

        $remote = ShopProducts::where('id', $request->input('productId'))->firstOrFail();
        $sale = ShopSales::where('product_id', $request->input('productId'));

        $product = new OrderedProduct();
        $product->order_id = $orderId;
        $product->product_id = $request->input('productId');
        $product->product_name = $request->input('productName');

        $price = 0;
        if ($sale->exists()) {
            $salePrd = $sale->firstOrFail();
            if (!Carbon::parse($salePrd->deleted_at)->isPast()) {
                $finalPrice = $remote->price * ($salePrd->reduction / 100);
                $price = $salePrd->reduction;
                $product->price = $remote->price - $finalPrice;
            } else {
                $product->price = $remote->price;
            }
        } else {
            $product->price = $remote->price;
        }

        $product->quantity = 1;
        $product->save();

        if ($request->has('saleId')) {
            $sale = new OrderSales();
            $sale->order_id = $orderId;
            $sale->sale_id = $request->input('saleId');
            $sale->save();
        }

        $data = ShopProducts::where('id', $remote->id);

        if ($data->exists()) {
            $prd = $data->firstOrFail();

            $view = view('shop.checkout')
                ->with('productName', $prd->name)
                ->with('productPrice', $product->price + $carrierPrice)
                ->with('originalPrice', $prd->price)
                ->with('discounted', $price)
                ->with('productDescription', strip_tags($prd->description))
                ->with('payment', true)
                ->with('success', false)
                ->with('failed', false)
                ->with('orderId', $orderId);

            if ($prd->image_path !== null) {
                $view->with('productURL', 'https://autumn.rsiniya.uk/attachments/' . $prd->image_path);
            } else {
                $view->with('productURL', 'https://autumn.rsiniya.uk/attachments/E1dC5nCVCCSnYwTmUTS7JMYAZiwOeb1xa8XCFPmu4j');
            }

            return $view;
        } else {
            return redirect('https://shop.fluffici.eu')
                ->with('toast', 'This product is not available.');
        }
    }

    public function payment(Request $request) {
        $orderId = $request->input('order-id');
        $paymentType = $request->input('payment-type');

        $order = ShopOrders::where('order_id', $orderId);
        if ($order->exists()) {
            $orderData = $order->firstOrFail();
            $products = OrderedProduct::where('order_id', $orderData->order_id)->firstOrFail();

            if ($orderData->status !== "PROCESSING") {
                return redirect('https://shop.fluffici.eu');
            }

            switch ($paymentType) {
                case "free": {
                    if (!$products->price <= 0) {
                        return redirect('https://shop.fluffici.eu');
                    }

                    $payment = new OrderPayment();
                    $payment->order_id = $orderData->order_id;
                    $payment->status = 'PAID';
                    $payment->transaction_id = Uuid::uuid4();
                    $payment->provider = 'Fluffici';
                    $payment->price = 0;
                    $payment->save();

                    return $this->renderSuccess('free', $orderData);
                }
                case "bank-card": {
                    $this->processPayment([], $orderData);
                }
                break;
                case "outing": {
                    ShopOrders::updateOrCreate(
                        ['order_id' => $orderData->order_id],
                        [
                            'status' => 'OUTING'
                        ]
                    );

                    return $this->renderSuccess('outing', $orderData);
                }
            }
        }

        return redirect('https://shop.fluffici.eu');
    }

    private function processPayment($data, ShopOrders $order)
    {
        //TODO: Payment SDK logic

        $this->sendEmail('payment-failed', $order);
    }

    private function renderSuccess($data, ShopOrders $orders)
    {
        $this->sendEmail('payment-' . $data . '-success', $orders);

        $prd = OrderedProduct::where('order_id', $orders->order_id)->firstOrFail();
        $product = ShopProducts::where('id', $prd->product_id)->firstOrFail();
        $orderCarrier = OrderCarrier::where('order_id', $orders->order_id)->firstOrFail();
        $carrier = ShopCarriers::where('slug', $orderCarrier->carrier_name)->firstOrFail();

        return view('shop.checkout')
            ->with('productName', $product->name)
            ->with('productPrice', $prd->price + $carrier->price)
            ->with('originalPrice', $product->price)
            ->with('productDescription', strip_tags($product->description))
            ->with('discounted', 0)
            ->with('productURL', 'https://autumn.rsiniya.uk/attachments/' . $product->image_path)

            ->with('success', true)
            ->with('failed', false)
            ->with('order', $orders)
            ->with('type', $data);
    }

    private function sendEmail($slug, ShopOrders $order): void
    {
        event(new OrderUpdateEvent($order, $slug));
    }

    private function qrValidate(Request $request){
        $orderId = ($request->input('orderId') !== null) ? base64_decode($request->input('orderId')) : false;

        if ($orderId === false) {
            //
        }
    }
}
