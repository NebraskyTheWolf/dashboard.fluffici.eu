<?php

namespace App\Http\Controllers;

use App\Mail\UserApiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;

/**
 * Class ApiController
 *
 * This class represents the controller for API-related actions.
 * It extends the Controller class.
 *
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{


    public function __constructor()
    {
        $this->middleware('auth.api');
    }

    /**
     * Index method
     *
     * Retrieves user information based on the provided username and password.
     * If the user exists and the password is correct, a login is successful
     * and a response with a token and success message is returned.
     * If the user account is terminated, a response with an account restricted error message is returned.
     * If the user does not exist or the password is incorrect, a response with a credentials error message is returned.
     *
     * @param Request $request The request object containing the JSON data with the username and password.
     * @return \Illuminate\Http\JsonResponse The response JSON object containing the login status, token, error (if any), and message.
     */
    public function index(Request $request)
    {
        $data = json_decode(json_encode($request->all()), true);

        if ($data['username'] != null
            && $data['password'] != null) {

            $user = User::where('name', $data['username']);
            if ($user->exists()) {
                $user = $user->first();

                if (Hash::check($data['password'], $user->password)) {
                    if ($user->isTerminated()) {
                        return response()->json([
                            'status' => false,
                            'error' => 'ACCOUNT_RESTRICTED',
                            'message' => 'Your account is terminated.'
                        ]);
                    }

                    Mail::to($user->email)->send(new UserApiNotification());

                    return response()->json([
                            'status' => true,
                            'token' => $user->createUserToken(),
                            'message' => 'Login successful.'
                        ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'error' => 'CREDENTIALS',
                        'message' => 'Username or password is invalid.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'CREDENTIALS',
                    'message' => 'Username or password is invalid.'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'error' => 'CREDENTIALS',
                'message' => 'Username or password is invalid.'
            ]);
        }

    }
}
