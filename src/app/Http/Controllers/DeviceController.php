<?php

namespace App\Http\Controllers;

use App\Events\UpdateAudit;
use App\Models\DeviceAuthorization;
use App\Models\OrderIdentifiers;
use App\Models\ShopCustomer;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopVouchers;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\User;

class DeviceController extends Controller
{
    /**
     * Metoda index.
     *
     * Ze žádosti získá ID zařízení a provede kontroly autorizace.
     * V případě, že je zařízení autorizováno, vrátí JSON odpověď s tokenem,
     * což naznačuje, že zařízení je platné. Pokud zařízení není autorizováno,
     * vrátí se JSON odpověď s chybovou zprávou.
     *
     * @param Request $request Objekt HTTP žádosti.
     *
     * @return JsonResponse JSON odpověď se statusem, tokenem (pokud je zařízení autorizováno),
     *                     nebo chybovou zprávou (pokud zařízení není autorizováno).
     */
    public function index(Request $request): JsonResponse
    {
        $deviceId =  $request->query('deviceId');

        if ($deviceId == null) {
            return response()->json([
               'status' => false,
               'error' => 'CHYBÍ_DEVICE_ID',
               'message' => "Chybí deviceId"
            ]);
        }

        $device = DeviceAuthorization::where('deviceId', $deviceId);
        if ($device->exists()) {
            $device = $device->first();
            if ($device->restricted) {
                return response()->json([
                    'status' => false,
                    'error' => "ZAŘÍZENÍ_OMEZENO",
                    'message' => "Toto zařízení bylo omezeno, obraťte se na svého nadřízeného správce."
                ]);
            }

            $user = User::where('id', $device->linked_user)->first();
            if ($user->isTerminated()) {
                return response()->json([
                    'status' => false,
                    'error' => "ÚČET_UKONČEN",
                    'message' => "Nemůžete používat toto zařízení, protože váš účet je ukončen."
                ]);
            }

            $device->update([
                'status' => "Používá se"
            ]);

            return response()->json([
                'status' => true,
                'data' => [
                    'username' => $user->name,
                    'token' => $user->createUserToken()
                ],
                'message' => "Platné zařízení."
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' =>  "Zařízení_NENALEZENO",
                'message' => "Toto zařízení je neautorizované."
            ]);
        }
    }

    /**
     * Metoda objednávek.
     *
     * Načte všechny objednávky obchodu a vrátí JSON odpověď s veřejným identifikátorem,
     * celým jménem zákazníka a e-mailem pro každou objednávku.
     *
     * @param Request $request Objekt HTTP žádosti.
     *
     * @return JsonResponse JSON odpověď se statusem, daty (pole objednávek),
     *                     a zprávou o úspěchu.
     */
    public function orders(Request $request): JsonResponse
    {
        $orderList = [];
        foreach (ShopOrders::all() as $singleOrder) {
            $orderList[] = $this->formatOrder($singleOrder);
        }
        return response()->json([
            'status' => true,
            'data' => $orderList,
            'message' => "Objednávky úspěšně načteny."
        ]);
    }

    /**
     * Formátuje data objednávky do specifického formátu.
     *
     * @param mixed $order Data objednávky k formátování.
     * @return array Formátovaná data objednávky.
     */
    private function formatOrder(ShopOrders $order): array
    {
        $publicData = OrderIdentifiers::where('order_id', $order->order_id)->first();
        return [
            'id' => $publicData->public_identifier,
            'customer' => [
                'fullname' => $order->first_name . ' ' . $order->last_name,
                'email' => $order->email
            ]
        ];
    }

    /**
     * Získá všechny zákazníky z databáze.
     *
     * @param Request $request Objekt žádosti.
     *
     * @return JsonResponse JSON odpověď obsahující načtené zákazníky.
     */
    public function customers(Request $request): JsonResponse
    {
        $customer = [];
        $customers = ShopOrders::all();

        foreach ($customers as $customer) {
            $customer[] = [
                'id' => $customer->id,
                'name' => $customer->first_name . ' ' . $customer->last_name,
                'email' => $customer->email
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $customer,
            'message' => "Zákazníci úspěšně načteni."
        ]);
    }

    /**
     * Retrieve all products from the database
     *
     * @param Request $request The incoming request object
     *
     * @return JsonResponse The JSON response object containing the retrieved products
     */
    public function products(Request $request): JsonResponse
    {
        $product = [];
        $products = ShopProducts::all();

        foreach ($products as $product) {
            $product[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $product,
            'message' => "Products retrieved successfully."
        ]);
    }

    /**
     * Fetches a product based on the provided UPC-A code.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse A response with information about the requested product.
     * If the product is found, it will have the following structure:
     *                     {
     *                         "status": true,
     *                         "data": {
     *                             "id": int,
     *                             "name": string,
     *                             "price": float
     *                         },
     *                         "message": "Product retrieved successfully."
     *                     }
     * If the UPC-A code is missing in the query parameters, the response will be:
     *                     {
     *                         "status": true,
     *                         "error": "MISSING_PRODUCT_ID",
     *                         "message": "The product id is missing in the query parameters."
     *                     }
     * If the product is not found, the response will be:
     *                     {
     *                         "status": false,
     *                         "error": "PRODUCT_NOT_FOUND",
     *                         "message": "Product not found."
     *                     }
     */
    public function fetchProduct(Request $request): JsonResponse
    {
        $ean13Code = $request->query('bid');

        if ($ean13Code == null) {
            return response()->json([
                'status' => true,
                'error' => "MISSING_PRODUCT_ID",
                'message' => "The product id is missing in the query parameters."
            ]);
        }

        $product = new ShopProducts();
        $dbg = $product->getProductFromUpcADBG($ean13Code);
        $product = $product->getProductFromUpcA($ean13Code);

        if ($product != null) {
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $product->getAvailableProducts()
                ],
                'message' => "Product retrieved successfully."
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => "PRODUCT_NOT_FOUND",
                'message' => "Product not found.",
                'data' => [
                    'bid' => $dbg
                ]
            ]);
        }
    }

    /**
     * Increments the quantity of a product based on its EAN13 code.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse The JSON response containing the updated product data.
     */
    public function incrementProduct(Request $request): JsonResponse
    {
        $ean13Code = $request->query('bid');

        // Use a guard clause to handle the condition where ean13Code is missing
        if ($ean13Code == null) {
            return response()->json([
                'status' => false,
                'error' => "MISSING_PRODUCT_ID",
                'message' => "The product id is missing in the query parameters."
            ]);
        }

        $product = (new ShopProducts())->getProductFromUpcA($ean13Code);

        // Use a guard clause to handle the condition where product is null
        if ($product == null) {
            return response()->json([
                'status' => false,
                'error' => "PRODUCT_NOT_FOUND",
                'message' => "Product not found."
            ]);
        }

        // At this point, we know that product is not null
        $product->createOrGetInventory();

        $predict = $product->getAvailableProducts() + 1;

        $product->incrementQuantity();

        if ($product->getAvailableProducts() != $predict) {
            return response()->json([
                'status' => false,
                'error' => "INCREMENT_ERROR",
                'message' => "Product quantity miss-match."
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->getAvailableProducts()
            ],
            'message' => "Product quantity incremented successfully."
        ]);
    }

    /**
     * Retrieves the voucher code information based on the voucherId query.
     *
     * @param Request $request The HTTP request object.
     *
     * @return JsonResponse A JSON response with voucher code details.
     */
    public function voucherInfo(Request $request): JsonResponse
    {
        $encodedData = $request->query('encodedData');

        if ($encodedData == null) {
            return response()->json([
                'status' => false,
                'error' => 'MISSING_VOUCHER_ID',
                'message' => "The voucherId is missing"
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

        if ($result === 1) {
            $voucher = ShopVouchers::where('code', $voucherCode);
            if ($voucher->exists()) {
                $voucher = $voucher->first();
                $customer = ShopCustomer::where('customer_id', $voucher->customer_id)->first();

                return response()->json([
                    'status' => true,
                    'data' => [
                        'balance' => $voucher->money,
                        'isExpired' => $voucher->isExpired(),
                        'isRestricted' => $voucher->isRestricted(),
                        'customer' => [
                            'first_name' => $customer->first_name,
                            'last_name' => $customer->last_name,
                            'email' => $customer->email
                        ],
                        'expireAt' => Carbon::parse($voucher->expiration)->diffForHumans()
                    ],
                    'message' => "Voucher code details retrieved successfully!"
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => "VOUCHER_NOT_FOUND",
                    'message' => "The voucher code does not exist."
                ]);
            }
        } else if ($result === 0) {
            return response()->json([
                'status' => false,
                'error' => 'VOUCHER_REJECTION',
                'message' => 'The voucher is tampered, DO NOT USE!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => 'SIGNATURE_VERIFICATION_ERROR',
                'message' => 'Error occurred during signature verification.'
            ]);
        }
    }
}
