<?php

namespace App\Http\Controllers;

use App\Events\UpdateAudit;
use App\Models\DeviceAuthorization;
use App\Models\OrderIdentifiers;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopVouchers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Orchid\Platform\Models\User;

class DeviceController extends Controller
{
    /**
     * index method.
     *
     * Retrieves the device ID from the request and performs authorization checks.
     * If the device is authorized, it returns a JSON response with a token,
     * indicating that the device is valid. If the device is unauthorized,
     * it returns a JSON response with an error message.
     *
     * @param Request $request The HTTP request object.
     *
     * @return JsonResponse A JSON response with status, token (if the device is authorized),
     *                     or an error message (if the device is unauthorized).
     */
    public function index(Request $request): JsonResponse
    {
        $deviceId =  $request->query('deviceId');

        if ($deviceId == null) {
            return response()->json([
               'status' => false,
               'error' => 'MISSING_DEVICE_ID',
               'message' => "The deviceId is missing"
            ]);
        }

        $device = DeviceAuthorization::where('deviceId', $deviceId);
        if ($device->exists()) {
            $device = $device->first();
            if ($device->restricted) {
                return response()->json([
                    'status' => false,
                    'error' => "DEVICE_RESTRICTED",
                    'message' => "This device was restricted, please refer to your superior admin."
                ]);
            }

            $user = User::where('id', $device->linked_user)->first();
            if ($user->isTerminated()) {
                return response()->json([
                    'status' => false,
                    'error' => "ACCOUNT_TERMINATED",
                    'message' => "You are not allowed to use this device, because your account is terminated."
                ]);
            }

            $device->update([
                'status' => "In Use"
            ]);

            return response()->json([
                'status' => true,
                'data' => [
                    'username' => $user->name,
                    'token' => $user->createUserToken()
                ],
                'message' => "Valid device."
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' =>  "DEVICE_NOT_FOUND",
                'message' => "This device is unauthorized."
            ]);
        }
    }

    /**
     * orders method.
     *
     * Retrieves all shop orders and returns a JSON response with the public identifier,
     * customer full name, and email for each order.
     *
     * @param Request $request The HTTP request object.
     *
     * @return JsonResponse A JSON response with status, data (an array of orders),
     *                     and a success message.
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
            'message' => "Orders retrieved successfully."
        ]);
    }

    /**
     * Formats the order data into a specified format.
     *
     * @param mixed $order The order data to be formatted.
     * @return array The formatted order data.
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
     * Retrieve all customers from the database.
     *
     * @param Request $request The request object.
     *
     * @return JsonResponse The JSON response containing the retrieved customers.
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
            'message' => "Customers retrieved successfully."
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
        if (RateLimiter::tooManyAttempts('fetch-product:'. $request->input('user_id'), $perMinute = 1)) {
            return response()->json([
                'status' => false,
                'error' => "RATE_LIMITED",
                'message' => "Rate Limited."
            ]);
        }

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
            RateLimiter::hit('fetch-product:'. $request->input('user_id'), 2);

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
        if (RateLimiter::tooManyAttempts('increment-product:'. $request->input('user_id'), $perMinute = 1)) {
            return response()->json([
                'status' => false,
                'error' => "RATE_LIMITED",
                'message' => "Rate Limited."
            ]);
        }

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

        RateLimiter::hit('increment-product:'. $request->input('user_id'), 2);

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
        $voucherCode = $request->query('voucherId');

        if ($voucherCode == null) {
            return response()->json([
                'status' => false,
                'error' => 'MISSING_VOUCHER_ID',
                'message' => "The voucherId is missing"
            ]);
        }

        $voucher = ShopVouchers::where('code', $voucherCode);
        if ($voucher->exists()) {
            $voucher = $voucher->first();

            return response()->json([
                'status' => true,
                'data' => [
                    'balance' => $voucher->amount
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
    }

}
