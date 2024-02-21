<?php

namespace App\Http\Controllers;

use App\Events\UpdateAudit;
use App\Models\OrderedProduct;
use App\Models\OrderPayment;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopVouchers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\User;

class PaymentController extends Controller
{

    /**
     * Process the payment for an order.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse
     *   The JSON response indicating the payment status.
     */
    public function index(Request $request): JsonResponse
    {
        $orderId =  $request->query('orderId');
        $paymentType = $request->query('paymentType');
        $encodedData = $request->query('encodedData');

        if ($orderId == null || $paymentType == null) {
            return response()->json([
                'status' => false,
                'error' => 'MISSING_ID',
                'message' => 'No order ID was found.'
            ]);
        }

        $order = ShopOrders::where('order_id', $orderId);
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'error' => 'INVALID_ORDER',
                'message' => 'This order does not exists.'
            ]);
        }
        $order = $order->first();

        $product = OrderedProduct::where('order_id', $orderId)->first();
        $product = ShopProducts::where('id', $product->product_id)->first();

        switch ($paymentType) {
            case 'VOUCHER':
            {
                if ($encodedData == null) {
                    return response()->json([
                        'status' => false,
                        'error' => 'MISSING_VOUCHER',
                        'message' => 'No voucher body was found.'
                    ]);
                }

                $storage = Storage::disk('public');
                if (!$storage->exists('security.cert')) {
                    return response()->json([
                        'status' => false,
                        'error' => 'SIGNATURE_REJECTION',
                        'message' => 'Unable to check the request signature.'
                    ]);
                }

                $key = openssl_pkey_get_public($storage->get('security.cert'));
                $data = json_decode(base64_decode($encodedData), true);

                $voucherCode = base64_decode($data['data']);
                $result = openssl_verify($voucherCode, base64_decode(strtr($data['signature'], '-_', '+/')), $key, OPENSSL_ALGO_SHA256);

                if ($result == 1) {
                    $voucher = ShopVouchers::where('code', $voucherCode);
                    if ($voucher->exists()) {
                        $voucherData = $voucher->first();

                        if ($voucherData->isExpired()) {
                            return response()->json([
                                'status' => false,
                                'error' => 'VOUCHER_EXPIRED',
                                'message' => 'The voucher has expired.'
                            ]);
                        }

                        if (!($voucherData->money < $product->getNormalizedPrice())) {
                            $voucherData->update([
                                'money' => $voucherData->money - $product->getNormalizedPrice()
                            ]);

                            $payment = new OrderPayment();
                            $payment->order_id = $order->order_id;
                            $payment->status = 'PAID';
                            $payment->transaction_id = \Ramsey\Uuid\Uuid::uuid4();
                            $payment->provider = 'Voucher #' . $voucherData->id;
                            $payment->price = $product->getNormalizedPrice();
                            $payment->save();

                            $order->update([
                                'status' => 'DELIVERED'
                            ]);

                            event(new UpdateAudit('order_payment', 'Validated ' . substr($orderId, 0, 8) . ' payment with a voucher.', $request->input('username')));

                            return response()->json([
                                'status' => true,
                                'message' => "Remaining balance " . number_format(($voucherData->money - $product->price)) . " Kc"
                            ]);
                        } else {
                            return response()->json([
                                'status' => false,
                                'error' => 'VOUCHER_REJECTION',
                                'message' => 'The voucher does not have enough money.'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'error' => 'VOUCHER_REJECTION',
                            'message' => 'The voucher is invalid.'
                        ]);
                    }
                } else if ($result == 0) {
                    return response()->json([
                        'status' => false,
                        'error' => 'VOUCHER_REJECTION',
                        'message' => 'The voucher is tampered, DO NOT USE!'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'error' => 'VOUCHER_REJECTION',
                        'message' => 'Unable to verify the signature.'
                    ]);
                }
            }
            case 'CASH':
            {
                $payment = new OrderPayment();
                $payment->order_id = $order->order_id;
                $payment->status = 'PAID';
                $payment->transaction_id = \Ramsey\Uuid\Uuid::uuid4();
                $payment->provider = 'Cash';
                $payment->price = $product->getNormalizedPrice();
                $payment->save();

                $order->update([
                    'status' => 'DELIVERED'
                ]);

                event(new UpdateAudit('order_payment', 'Validated ' . substr($orderId, 0, 8) . ' payment with cash.', $request->input('username')));

                return response()->json([
                    'status' => true,
                    'message' => 'The payment was successful'
                ]);
            }
            default:
            {
                return response()->json([
                    'status' => false,
                    'error' => 'BAD_REQUEST',
                    'message' => 'The payment request is denied.'
                ]);
            }
        }
    }


    /**
     * Fetches an order from the database based on the given order ID.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse The JSON response containing the order details.
     */
    public function fetchOrder(Request $request)
    {
        $orderId = $request->query('orderId');
        if ($orderId == null) {
            return response()->json([
                'status' => false,
                'error' => 'MISSING_ID',
                'message' => 'No order ID was found.'
            ]);
        }

        $order = ShopOrders::where('order_id', $orderId);
        $payment = OrderPayment::where('order_id', $orderId);
        $products = OrderedProduct::where('order_id', $orderId);


        if ($order->exists()) {
            $orderData = $order->first();

            if ($orderData->status == "COMPLETED"
                || $orderData->status == "DELIVERED") {
                return response()->json([
                    'status' => false,
                    'error' => 'ORDER_ALREADY_PROCESSED',
                    'message' => 'This order has been already ' . strtolower($orderData->status) .' ' . \Carbon\Carbon::parse($orderData->updated_at)->diffForHumans() . '.'
                ]);
            }

            $data = [];
            $data['order'] = $order->first();

            if ($payment->exists()) {
                $data['payment'] = $payment->first();
            } else {
                $data['payment'] = false;
            }

            if ($products->exists()) {
                $data1 = $products->first();
                $data['product'] = $data1;
                $prd = ShopProducts::where('id', $data1->product_id)->first();
                $data['productURL'] = $prd->getImage();
            } else {
                $data['product'] = false;
            }

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => 'ORDER_NOT_FOUND',
                'message' => 'This order does not exists in our records.'
            ]);
        }
    }

    /**
     * Refunds an order based on the given order ID.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse The JSON response indicating the refund status.
     */
    public function refund(Request $request): JsonResponse
    {
        $orderId = $request->query('orderId');
        if($orderId == null) {
            return response()->json([
                'status' => false,
                'error' => 'MISSING_ID',
                'message' => 'No order ID was found.'
            ]);
        }

        $order = ShopOrders::where('order_id', $orderId);
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'error' => 'INVALID_ORDER',
                'message' => 'This order does not exist.'
            ]);
        }
        $order = $order->first();

        $order->update(['status' => 'REFUNDED']);

        $currentPayment = OrderPayment::where('order_id', $orderId)->where('status', 'PAID');
        if ($currentPayment->exists()) {
            $currentPayment = $currentPayment->first();

            if (str_contains($currentPayment->provider, 'Voucher')) {
                return response()->json([
                    'status' => false,
                    'error' => 'NOT_REFUNDABLE',
                    'message' => 'The order was paid with a voucher code and this order is not refundable.'
                ]);
            }

            $payment = new OrderPayment();
            $payment->order_id = $order->order_id;
            $payment->status = 'REFUNDED';
            $payment->transaction_id = $currentPayment->transaction_id;
            $payment->provider = 'Fluffici';
            $payment->price = $currentPayment->price;
            $payment->save();

            event(new UpdateAudit('order_refund', 'Refunded ' . substr($orderId, 0, 8) . ' order.', $request->input('username')));

            return response()->json([
                'status' => true,
                'message' => 'Order has been refunded successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => 'ORDER_NOT_PAID',
                'message' => 'The order was not paid yet.'
            ]);
        }
    }

    /**
     * Cancels an order in the database based on the given order ID.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse The JSON response indicating the status and message of the cancellation.
     */
    public function cancel(Request $request): JsonResponse
    {
        $orderId = $request->query('orderId');
        if ($orderId == null) {
            return response()->json([
                'status' => false,
                'error' => 'MISSING_ID',
                'message' => 'No order ID was found.'
            ]);
        }

        $order = ShopOrders::where('order_id', $orderId);
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'error' => 'INVALID_ORDER',
                'message' => 'This order does not exist.'
            ]);
        }

        $order = $order->first();
        $order->update(['status' => 'CANCELLED']);

        event(new UpdateAudit('order_cancel', 'Cancelled ' . substr($orderId, 0, 8) . ' order.', $request->input('username')));

        return response()->json([
            'status' => true,
            'message' => 'Order has been cancelled successfully.'
        ]);
    }
}
