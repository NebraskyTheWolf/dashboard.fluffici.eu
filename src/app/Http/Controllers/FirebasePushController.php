<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;

class FirebasePushController extends Controller
{

    /**
     * Sends a notification to a user using FCM (Firebase Cloud Messaging).
     *
     * This method checks if the user is connected to FCM and sends a notification
     * to the user with the specified title and body. If the notification is successfully
     * sent, it returns a JSON response with a status of true and a message saying "Notification sent!".
     *
     * If the user is not connected to FCM, it returns a JSON response with a status of false,
     * an error code of "NOT_FCM_USER", and an error message saying "This user is not connected on FCM.".
     *
     * If there is an error while sending the notification, it returns a JSON response with
     * a status of false, an error code of "FCM_ERROR", and an error message saying "Unable to send the notification.".
     *
     * @param Request $request The HTTP request object that contains the user ID, title, and body of the notification.
     *
     * @return JsonResponse A JSON response with the status and message indicating the result of the notification sending process.
     */
    public function notification(Request $request): JsonResponse
    {
        $user = User::where('id', $request->input('user_id'))->first();

        if (!$user->is_fcm) {
            return response()->json([
                'status' => false,
                'error' => 'NOT_FCM_USER',
                'message' => 'This user is not connected on FCM.'
            ]);
        }

        $title = $request->input('title');
        $body = $request->input('body');

        $result = $user->sendFCMNotification($title, $body);
        if (!$result) {
            return response()->json([
                'status' => false,
                'error' => 'FCM_ERROR',
                'message' => 'Unable to send the notification.'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Notification sent!'
        ]);
    }
}
