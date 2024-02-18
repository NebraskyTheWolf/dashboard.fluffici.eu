<?php

namespace App\Http\Controllers;

use App\Events\UpdateAudit;
use App\Models\DeviceAuthorization;
use App\Models\OrderIdentifiers;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

            event(new UpdateAudit("devices", "Authorized " . $deviceId, "System"));

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
        $order = [];
        $orders = ShopOrders::all();

        foreach ($orders as $order) {
            $publicData = OrderIdentifiers::where('order_id', $order->order_id)->first();

            $order[] = [
                'id' => $publicData->public_identifier,
                'customer' => [
                    'fullname' => $order->first_name . ' ' . $order->last_name,
                    'email' => $order->email
                ]
            ];
        }
        return response()->json([
            'status' => true,
            'data' => $order,
            'message' => "Orders retrieved successfully."
        ]);
    }

    /**
     * Retrieve all customers from the database.
     *
     * @param Request $request The request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the retrieved customers.
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
     * Increment the quantity of a product.
     *
     * @param Request $request The request object containing the product ID as a query parameter.
     *
     * @return JsonResponse The response JSON containing the updated product information and a success message
     *                    or an error message if the product ID is missing or the product is not found.
     */
    public function incrementProduct(Request $request): JsonResponse
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
        $product = $product->getProductFromUpcA($ean13Code);
        // In case the inventory was never created
        $product->createOrGetInventory();

        if ($product != null) {
            $product->incrementQuantity();
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
        } else {
            return response()->json([
                'status' => false,
                'error' => "PRODUCT_NOT_FOUND",
                'message' => "Product not found."
            ]);
        }
    }
}
