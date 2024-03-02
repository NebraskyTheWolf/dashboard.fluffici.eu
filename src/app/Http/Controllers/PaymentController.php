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
use Ramsey\Uuid\Uuid;

class PaymentController extends Controller
{
    /**
     * Zpracuje platbu za objednávku.
     *
     * @param Request $request Objekt HTTP požadavku.
     * @return JsonResponse
     *   JSON odpověď indikující stav platby.
     */
    public function index(Request $request): JsonResponse
    {
        $orderId =  $request->query('orderId');
        $paymentType = $request->query('paymentType');
        $encodedData = $request->query('encodedData');

        if ($orderId == null || $paymentType == null) {
            return response()->json([
                'status' => false,
                'error' => 'CHYBEJICI_ID',
                'message' => 'Nebylo nalezeno žádné ID objednávky.'
            ]);
        }

        $order = ShopOrders::where('order_id', $orderId);
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'error' => 'NEPLATNA_OBJEDNAVKa',
                'message' => 'Tato objednávka neexistuje.'
            ]);
        }
        $order = $order->first();

        if ($order->customer_id === null) {
            return response()->json([
                'status' => false,
                'error' => 'NEPLATNA_OBJEDNAVKa',
                'message' => 'Zákaznické ID není přítomno.'
            ]);
        }

        $product = OrderedProduct::where('order_id', $orderId)->first();
        $product = ShopProducts::where('id', $product->product_id)->first();

        switch ($paymentType) {
            case 'VOUCHER':
            {
                if ($encodedData == null) {
                    return response()->json([
                        'status' => false,
                        'error' => 'CHYBEJICI_POUKAZ',
                        'message' => 'Nebylo nalezeno tělo poukazu.'
                    ]);
                }

                $storage = Storage::disk('public');
                if (!$storage->exists('security.cert')) {
                    return response()->json([
                        'status' => false,
                        'error' => 'ODMITNUTI_PODPISU',
                        'message' => 'Nelze ověřit podpis požadavku.'
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
                                'error' => 'POUKAZ_VYEXPIROVAN',
                                'message' => 'Poukaz vypršel.'
                            ]);
                        }

                        if ($voucherData->isRestricted()) {
                            return response()->json([
                                'status' => false,
                                'error' => 'ODMITNUTI_POUKAZU',
                                'message' => 'Poukaz byl zablokován administrátorem.'
                            ]);
                        }

                        if (!$voucherData->isCustomerAssigned($order->customer_id)) {
                            return response()->json([
                                'status' => false,
                                'error' => 'ODMITNUTI_POUKAZU',
                                'message' => 'Poukaz není přiřazen zákazníkovi.'
                            ]);
                        }

                        if (!($voucherData->money < $product->getNormalizedPrice())) {
                            $voucherData->update([
                                'money' => $voucherData->money - $product->getNormalizedPrice()
                            ]);

                            $payment = new OrderPayment();
                            $payment->order_id = $order->order_id;
                            $payment->status = 'PAID';
                            $payment->transaction_id = Uuid::uuid4();
                            $payment->provider = 'Poukaz #' . $voucherData->id;
                            $payment->price = $product->getNormalizedPrice();
                            $payment->save();

                            $order->update([
                                'status' => 'DELIVERED'
                            ]);

                            event(new UpdateAudit('order_payment', 'Ověřena ' . substr($orderId, 0, 8) . ' platba pomocí poukazu.', $request->input('username')));

                            return response()->json([
                                'status' => true,
                                'message' => "Zbývající zůstatek " . number_format(($voucherData->money - $product->price)) . " Kc"
                            ]);
                        } else {
                            if (!$voucherData->money <= 0) {
                                $calculatedPrice = $product->getNormalizedPrice() - $voucherData->money;

                                $payment = new OrderPayment();
                                $payment->order_id = $order->order_id;
                                $payment->status = 'PARTIALLY_PAID';
                                $payment->transaction_id = Uuid::uuid4();
                                $payment->provider = '#1 Poukaz #' . $voucherData->id;
                                $payment->price = $voucherData->money ;
                                $payment->save();

                                $voucherData->update([
                                    'money' => 0,
                                    'restricted' => true
                                ]);

                                return response()->json([
                                    'status' => true,
                                    'message' => "Zbývající částka k úhradě " . number_format(($calculatedPrice)) . " Kc (Částečně zaplaceno)",
                                    'data' => [
                                        // Android PDA app internal.
                                        'intent_redirect' => 'PartialPayment',
                                        'payment' => [
                                            'type' => 'PARTIAL',
                                            'payment' => $payment
                                        ]
                                    ]
                                ]);
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'error' => 'ODMITNUTI_POUKAZU',
                                    'message' => 'Poukaz nemá žádný zbývající zůstatek.'
                                ]);
                            }
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'error' => 'ODMITNUTI_POUKAZU',
                            'message' => 'Poukaz je neplatný.'
                        ]);
                    }
                } else if ($result == 0) {
                    return response()->json([
                        'status' => false,
                        'error' => 'ODMITNUTI_POUKAZU',
                        'message' => 'Bylo manipulováno s poukazem, NEPOUŽÍVEJTE!'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'error' => 'ODMITNUTI_POUKAZU',
                        'message' => 'Nelze ověřit podpis.'
                    ]);
                }
            }
            case 'CASH':
            {
                $partialPayment = OrderPayment::where('order_id',  $order->order_id)->where('status', 'PARTIALLY_PAID');
                if ($partialPayment->exists()) {
                    $partialPayment = $partialPayment->first();

                    $price = $product->getNormalizedPrice() - $partialPayment->price;

                    $payment = new OrderPayment();
                    $payment->order_id = $order->order_id;
                    $payment->status = 'PAID';
                    $payment->transaction_id = $partialPayment->transaction_id;
                    $payment->provider = '#2 Hotovost (' . $price . ' Kc)';
                    $payment->price = $price;

                } else {
                    $payment = new OrderPayment();
                    $payment->order_id = $order->order_id;
                    $payment->status = 'PAID';
                    $payment->transaction_id = Uuid::uuid4();
                    $payment->provider = 'Hotovost';
                    $payment->price = $product->getNormalizedPrice();
                }

                $payment->save();

                $order->update([
                    'status' => 'DELIVERED'
                ]);

                event(new UpdateAudit('order_payment', 'Ověřena ' . substr($orderId, 0, 8) . ' platba hotově.', $request->input('username')));

                return response()->json([
                    'status' => true,
                    'message' => 'Platba byla úspěšná'
                ]);
            }
            default:
            {
                return response()->json([
                    'status' => false,
                    'error' => 'ŠPATNÝ_POŽADAVEK',
                    'message' => 'Platba je zamítnuta.'
                ]);
            }
        }
    }

    /**
     * Získává objednávku z databáze na základě daného ID objednávky.
     *
     * @param Request $request Objekt HTTP požadavku.
     * @return JsonResponse JSON odpověď obsahující detaily objednávky.
     */
    public function fetchOrder(Request $request): JsonResponse
    {
        $orderId = $request->query('orderId');
        if ($orderId == null) {
            return response()->json([
                'status' => false,
                'error' => 'CHYBEJICI_ID',
                'message' => 'Nebylo nalezeno žádné ID objednávky.'
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
                    'error' => 'OBJEDNAVKa_JIZ_ZPRACOVANA',
                    'message' => 'Tato objednávka byla již ' . strtolower($orderData->status) .' ' . \Carbon\Carbon::parse($orderData->updated_at)->diffForHumans() . '.'
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
                'error' => 'OBJEDNAVKa_NEBYLA_NALEZENA',
                'message' => 'Tato objednávka neexistuje v našich záznamech.'
            ]);
        }
    }

    /**
     * Vrátí objednávku na základě daného ID objednávky.
     *
     * @param Request $request Objekt HTTP požadavku.
     * @return JsonResponse JSON odpověď indikující stav vrácení.
     */
    public function refund(Request $request): JsonResponse
    {
        $orderId = $request->query('orderId');
        if($orderId == null) {
            return response()->json([
                'status' => false,
                'error' => 'CHYBEJICI_ID',
                'message' => 'Nebylo nalezeno žádné ID objednávky.'
            ]);
        }

        $order = ShopOrders::where('order_id', $orderId);
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'error' => 'NEPLATNA_OBJEDNAVKA',
                'message' => 'Tato objednávka neexistuje.'
            ]);
        }
        $order = $order->first();

        if ($order->status === "REFUNDED") {
            return response()->json([
                'status' => false,
                'error' => 'NEPLATNA_OBJEDNAVKA',
                'message' => 'Tato objednávka byla již vrácena.'
            ]);
        }

        $currentPayment = OrderPayment::where('order_id', $orderId)->where('status', 'PAID');
        if ($currentPayment->exists()) {
            $currentPayment = $currentPayment->first();

            if (str_contains($currentPayment->provider, 'Poukaz')) {
                return response()->json([
                    'status' => false,
                    'error' => 'NENI_VRATITELNE',
                    'message' => 'Objednávka byla zaplacena pomocí kódu poukazu a tato objednávka není vratitelná.'
                ]);
            }

            $payment = new OrderPayment();
            $payment->order_id = $order->order_id;
            $payment->status = 'REFUNDED';
            $payment->transaction_id = $currentPayment->transaction_id;
            $payment->provider = 'Fluffici';
            $payment->price = $currentPayment->price;
            $payment->save();

            event(new UpdateAudit('order_refund', 'Vrácena ' . substr($orderId, 0, 8) . ' objednávka.', $request->input('username')));

            $order->update(['status' => 'REFUNDED']);

            return response()->json([
                'status' => true,
                'message' => 'Objednávka byla úspěšně vrácena.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => 'OBJEDNAVKA_NEPLACENA',
                'message' => 'Tato objednávka nebyla ještě zaplacena.'
            ]);
        }
    }

    /**
     * Zruší objednávku v databázi na základě daného ID objednávky.
     *
     * @param Request $request Objekt HTTP požadavku.
     * @return JsonResponse JSON odpověď indikující stav a zprávu o zrušení.
     */
    public function cancel(Request $request): JsonResponse
    {
        $orderId = $request->query('orderId');
        if ($orderId == null) {
            return response()->json([
                'status' => false,
                'error' => 'CHYBEJICI_ID',
                'message' => 'Nebylo nalezeno žádné ID objednávky.'
            ]);
        }

        $order = ShopOrders::where('order_id', $orderId);
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'error' => 'NEPLATNA_OBJEDNAVKA',
                'message' => 'Tato objednávka neexistuje.'
            ]);
        }

        $order = $order->first();

        if ($order->status === "CANCELLED") {
            return response()->json([
                'status' => false,
                'error' => 'NEPLATNA_OBJEDNAVKA',
                'message' => 'Tato objednávka byla již zrušena.'
            ]);
        }

        $order->update(['status' => 'CANCELLED']);

        event(new UpdateAudit('order_cancel', 'Zrušena ' . substr($orderId, 0, 8) . ' objednávka.', $request->input('username')));

        return response()->json([
            'status' => true,
            'message' => 'Objednávka byla úspěšně zrušena.'
        ]);
    }

    /**
     * Zkontroluje, zda je daná objednávka plně zaplacena.
     *
     * @param string $orderId ID objednávky.
     * @return bool
     */
    public function isOrderFullyPaid(string $orderId): bool
    {
        $orderPayments = OrderPayment::where('order_id', $orderId)->get();
        $orderProducts = OrderedProduct::where('order_id', $orderId)->get();

        // Žádné platby znamenají, že objednávka je zřejmě nezaplacena.
        if($orderPayments->isEmpty())
            return false;

        $totalPrice = 0;
        foreach($orderPayments as $payment)
            if($payment->status === 'PAID' || $payment->status === 'PARTIALLY_PAID')
                $totalPrice += $payment->price;

        $totalProductPrice = 0;
        foreach($orderProducts as $product)
            $totalProductPrice += $product->getNormalizedPrice();

        if ($totalPrice < $totalProductPrice)
            return false;
        return true;
    }
}
