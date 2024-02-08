<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $voucherCode = $request->query('voucherCode');
        $storage = \Illuminate\Support\Facades\Storage::disk('public');

        if ($voucherCode != null) {
            $voucher = \App\Models\ShopVouchers::where('code', $voucherCode);

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

                $response = \Httpful\Request::post(env("IMAGER_HOST", "http://85.215.202.21:3900/voucher/") . $voucherData->money, [
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
}
