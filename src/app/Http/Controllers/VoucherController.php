<?php

namespace App\Http\Controllers;

use App\Models\ShopVouchers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\User;

class VoucherController extends Controller
{
    /**
     * This method is used to process the index request and generate a voucher image.
     *
     * @param Request $request The request object containing the voucher code.
     *
     * @return mixed The generated voucher image or JSON response in case of errors.
     */
    public function index(Request $request)
    {
        $voucherCode = $request->query('voucherCode');

        if ($voucherCode != null) {
            $voucher = ShopVouchers::where('code', $voucherCode);

            if ($voucher->exists()) {
                $voucherData = $voucher->first();

                $storage = Storage::disk('public');
                if (!$storage->exists('security.key')) {
                    return response()->json([
                        'status' => false,
                        'error' => 'SIGNATURE',
                        'message' => 'Unable to check the request signature.'
                    ]);
                }

                $key = openssl_pkey_get_private($storage->get('security.key'));
                $signedData = openssl_sign($voucherData->code, $signature, $key, OPENSSL_ALGO_SHA256);
                if ($signedData == 0) {
                    return response()->json([
                        'error' => 'Cannot sign data.'
                    ]);
                }

                $response = \Httpful\Request::post(env("IMAGER_HOST", 'http://185.188.249.234:3900/voucher/'), [
                    'price' => $voucherData->money,
                    'properties' => base64_encode(stripslashes(json_encode([
                        'signature' => base64_encode($signature),
                        'data' => base64_encode($voucherData->code)
                    ], JSON_INVALID_UTF8_IGNORE)))
                ], "application/json")->expectsJson()->send();

                if ($response->code == 200) {
                    return response()->download(storage_path('app/public/' . $voucherData->code . '-code.png'));
                } else {
                    return response()->json([
                        'error' => 'The server was not responding correctly.'
                    ]);
                }
            } else {
                return response()->json([
                    'error' => 'This voucher code is invalid.'
                ]);
            }
        } else {
            return response()->json([
                'error' => 'This voucher code is invalid.'
            ]);
        }
    }

    /**
     * This method is used to process the datamatrix request and generate an order image with a datamatrix code.
     *
     * @param Request $request The request object containing the order ID.
     *
     * @return mixed The generated order image with datamatrix code or JSON response in case of errors.
     */
    public function datamatrix(Request $request)
    {
        if ($request->order_id == null) {
            return response()->json([
                'error' => 'Missing order ID'
            ]);
        }

        $response = \Httpful\Request::post(env("IMAGER_HOST", 'http://185.188.249.234:3900/order/'), [
            'orderId' => $request->order_id
        ], "application/json")->expectsJson()->send();

        if ($response->code == 200) {
            return response()->download(storage_path('app/public/' . $request->order_id . '-order.png'));
        } else {
            return response()->json([
                'error' => 'The server was not responding correctly.'
            ]);
        }
    }
}
