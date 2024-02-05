<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        $orderId =  $request->query('orderId');
        $paymentType = $request->query('paymentType');
        $encodedData = $request->query('encodedData');

        if ($orderId == null && $paymentType == null) {
            return response()->json([
                'status' => false,
                'error' => 'MISSING_ID',
                'message' => 'No order ID was found.'
            ]);
        }

        $order = \App\Models\ShopOrders::where('order_id', $orderId)->first();
        $product = \App\Models\OrderedProduct::where('order_id', $orderId)->first();

        switch ($paymentType) {
            case "VOUCHER_DEBUG":
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

                if ($paymentType === "VOUCHER_DEBUG") {
                    return response()->json([
                        'status' => false,
                        'error' => base64_decode(($encodedData)),
                        'message' => $data
                    ]);
                }

                $voucherCode = base64_decode($data['data']);
                $result = openssl_verify($voucherCode, base64_decode(strtr($data['signature'], '-_', '+/')), $key, OPENSSL_ALGO_SHA256);

                if ($result == 1) {
                    $voucher = \App\Models\ShopVouchers::where('code', $voucherCode);
                    if ($voucher->exists()) {
                        $voucherData = $voucher->first();
                        if (!($voucherData->money < $product->price)) {
                            $voucherData->update([
                                'money' => $voucherData->money - $product->price
                            ]);

                            $payment = new \App\Models\OrderPayment();
                            $payment->order_id = $order->order_id;
                            $payment->status = 'PAID';
                            $payment->transaction_id = \Ramsey\Uuid\Uuid::uuid4();
                            $payment->provider = 'Voucher #' . $voucherData->id;
                            $payment->price = $product->price;
                            $payment->save();

                            $order->update([
                                'status' => 'DELIVERED'
                            ]);

                            return response()->json([
                                'status' => true,
                                'message' => "Remaining balance " . number_format(($voucherData->money - $product->price)) . " Kc"
                            ]);
                        } else {
                            return response()->json([
                                'status' => false,
                                'error' => 'VOUCHER_REJECTION',
                                'message' => 'The voucher code does not have enough money.'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'error' => 'VOUCHER_REJECTION',
                            'message' => 'The voucher code is invalid.'
                        ]);
                    }
                } else if ($result == 0) {
                    return response()->json([
                        'status' => false,
                        'error' => 'VOUCHER_REJECTION',
                        'message' => 'The voucher code is tampered, DO NOT USE!'
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
                $payment = new \App\Models\OrderPayment();
                $payment->order_id = $order->order_id;
                $payment->status = 'PAID';
                $payment->transaction_id = \Ramsey\Uuid\Uuid::uuid4();
                $payment->provider = 'Cash';
                $payment->price = $product->price;
                $payment->save();

                $order->update([
                    'status' => 'DELIVERED'
                ]);

                return response()->json([
                    'status' => true,
                    'data' => [
                        'message' => 'The payment was successful'
                    ]
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

        $order = \App\Models\ShopOrders::where('order_id', $orderId);
        $payment = \App\Models\OrderPayment::where('order_id', $orderId);
        $products = \App\Models\OrderedProduct::where('order_id', $orderId);


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
                $prd = \App\Models\ShopProducts::where('id', $data1->product_id)->first();
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

}
