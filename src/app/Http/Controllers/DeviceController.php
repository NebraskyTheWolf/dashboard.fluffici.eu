<?php

namespace app\Http\Controllers;

use App\Events\UpdateAudit;
use App\Models\DeviceAuthorization;
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
    public function index(Request $request)
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
                    'data' => "DEVICE_RESTRICTED",
                    'message' => "This device was restricted, please refer to your superior admin."
                ]);
            }

            $user = User::where('id', $device->linked_user)->first();
            if ($user->isTerminated()) {
                return response()->json([
                    'status' => false,
                    'data' => "ACCOUNT_TERMINATED",
                    'message' => "You are not allowed to use this device, because your account is terminated."
                ]);
            }


            event(new UpdateAudit("devices", "Authorized " . $deviceId, "System"));

            return response()->json([
                'status' => true,
                'token' => $user->createUserToken(),
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
}
