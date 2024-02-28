<?php

namespace App\Http\Controllers;

use App\Mail\UserApiNotification;
use App\Mail\UserOtpMail;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\User;
use Random\RandomException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     * @return JsonResponse The response JSON object containing the login status, token, error (if any), and message.
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->validateInput($request);
        if ($data === false) {
            return $this->createErrorResponse('CREDENTIALS', 'Email or password is invalid.');
        }

        $user = $this->findByUsername($data['email']);
        if ($user === null) {
            return $this->createErrorResponse('CREDENTIALS', 'Email or password is invalid.');
        }

        if (!$this->validatePassword($data['password'], $user->password)) {
            return $this->createErrorResponse('CREDENTIALS', 'Email or password is invalid.');
        }

        if ($user->isTerminated()) {
            return $this->createErrorResponse('ACCOUNT_RESTRICTED', 'Your account is terminated.');
        }

        $otp = new UserOtp();
        $otp->user_id = $user->id;
        $otp->token = $this->generateNumericToken(8);
        $otp->expiry = Carbon::now()->addMinutes(30);
        $otp->save();

        Mail::to($user->email)->send(new UserOtpMail($user, $otp->token));

        return response()->json([
            'status' => true,
            'type' => 'OTP_REQUIRED'
        ]);
    }

    /**
     * Validates the OTP (One-Time Password) code provided by the user.
     *
     * @param Request $request The HTTP request object.
     *
     * @return JsonResponse The response indicating the result of the OTP validation.
     *                      If the provided OTP code is valid, the response will contain
     *                      a success status and a user token.
     *                      If the provided OTP code is invalid, the response will contain
     *                      an error status and an error message.
     *                      The error message will be "Your OTP code is invalid.".
     *                      The error status will be "CREDENTIALS".
     *                      If an error occurred during the OTP validation process,
     *                      an error response with status "INVALID_REQUEST" and message
     *                      "Unable to verify the OTP code." will be returned.
     */
    public function validateOtp(Request $request): JsonResponse
    {
        $data = $this->validateOtpInput($request);
        if ($data === false) {
            return $this->createErrorResponse('CREDENTIALS', 'Your OTP code is invalid.');
        }

        $otp = UserOtp::where('token', $data['code']);

        if ($otp->exists()) {
            $data = $otp->first();
            $user = User::where('id', $data->user_id)->first();

            $this->sendNotification($user);

            return $this->createSuccessResponse($user->createUserToken(), $user);
        }

        return response()->json([
            'status' => false,
            'error' => 'INVALID_REQUEST',
            'message' => 'Unable to verify the OTP code.'
        ]);
    }

    /**
     * Validates the input from the request.
     *
     * @param Request $request The request object containing the input data.
     *
     * @return array|bool The validated input data if it passes the validation, or false if any required fields are missing.
     */
    private function validateInput(Request $request): bool|array
    {
        $data = json_decode(json_encode($request->all()), true);

        if (empty($data['email']) || empty($data['password'])) {
            return false;
        }

        return $data;
    }

    /**
     * Validates the OTP input from the request.
     *
     * @param Request $request The HTTP request object.
     *                         The request object is used to get the input data.
     *
     * @return bool|array Returns either a boolean value or an associative array.
     *                    - If the 'code' field is empty in the request data,
     *                      returns false to indicate that the OTP input is invalid.
     *                    - Otherwise, returns the request data as an associative array.
     *                      The array contains the input data from the request.
     *                      The array format is the same as the original request data.
     */
    private function validateOtpInput(Request $request): bool|array
    {
        $data = json_decode(json_encode($request->all()), true);

        if (empty($data['code'])) {
            return false;
        }

        return $data;
    }

    /**
     * Finds a user by the given username.
     *
     * @param string $username The username of the user to be found.
     *
     * @return User|null The user object if found, null otherwise.
     */
    private function findByUsername(string $username): ?User
    {
        $user = User::where('name', $username);

        return $user->exists() ? $user->first() : null;
    }

    /**
     * Validates a password against a hashed user password.
     *
     * @param string $inputPassword The password to be validated.
     * @param string $userPassword The hashed user password to compare against.
     *
     * @return bool True if the password is valid, false otherwise.
     */
    private function validatePassword(string $inputPassword, string $userPassword): bool
    {
        return Hash::check($inputPassword, $userPassword);
    }

    /**
     * Sends a notification to the given user's email address.
     *
     * @param User $user The user to send the notification to.
     *
     * @return void
     */
    private function sendNotification(User $user): void
    {
        Mail::to($user->email)->send(new UserApiNotification());
    }

    /**
     * Creates an error response with the given error and message.
     *
     * @param string $error The error code or message to be included in the response.
     * @param string $message The error description or additional message to be included in the response.
     *
     * @return JsonResponse The JSON response indicating an error occurred.
     */
    private function createErrorResponse(string $error, string $message): JsonResponse
    {
        return response()->json([
            'status' => false,
            'error' => $error,
            'message' => $message
        ]);
    }

    /**
     * Creates a success response with the given token.
     *
     * @param string $token The token to be included in the response.
     *
     * @return JsonResponse The JSON response indicating a successful login.
     */
    private function createSuccessResponse(string $token, User $user): JsonResponse
    {
        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => [
                'username' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatarId' => $user->avatar_id
            ],
            'message' => 'Login successful.'
        ]);
    }

    /**
     * Fetches the EAN code for a given product ID.
     *
     * @param Request $request The HTTP request object.
     *                        Use the query() method to retrieve parameters from the request.
     *                        The 'productId' parameter is required.
     *
     * @return JsonResponse|BinaryFileResponse The EAN code for the given product ID, or an error response if the product ID is missing.
     *               The error response is in the form of an associative array with keys 'error_code' and 'error_message'.
     *               If the product ID is missing, the error code will be "MISSING_PRODUCT_ID" and the error message
     *               will be "The productId is missing".
     *               If the product ID is found, the EAN code will be returned as a string.
     */
    public function fetchEANCode(Request $request): JsonResponse|BinaryFileResponse
    {
        $productId = $request->query('productId');
        if ($productId == null) {
            return $this->createErrorResponse("MISSING_PRODUCT_ID", "The productId is missing");
        }


        $response = \Httpful\Request::post(env("IMAGER_HOST", 'http://185.188.249.234:3900/product/'), [
            'productId' => $productId
        ], "application/json")->expectsJson()->send();

        if ($response->code == 200) {
            return $this->fetchProductImage($productId);
        } else {
            return response()->json([
                'error' => 'The server was not responding correctly.'
            ]);
        }
    }

    /**
     * Fetches the product image for a given product.
     *
     * @param string $productId The ID of the product.
     *
     * @return JsonResponse|BinaryFileResponse The product image in case it exists, or a JSON response with an error message if it does not.
     *   - If the product image file exists in the storage, it will be downloaded.
     *   - If the product image file does not exist in the storage, a JSON response will be returned:
     *     - error: Not found in the storage.
     */
    private function fetchProductImage(string $productId): JsonResponse|BinaryFileResponse
    {
        $storage = Storage::disk('public');
        if ($storage->exists($productId . '-code128.png')) {
            return response()->download(storage_path('app/public/' . $productId . '-code128.png'));
        } else {
            return $this->createErrorResponse("FILE_NOT_FOUND", "Not found in the storage.");
        }
    }

    /**
     * Generate a numeric token.
     *
     * @param int $length The length of the token (default: 4).
     * @return string The generated numeric token.
     * @throws RandomException
     */
    private function generateNumericToken(int $length = 4): string
    {
        $i = 0;
        $token = "";

        while ($i < $length) {
            $token .= random_int(0, 9);
            $i++;
        }

        return $token;
    }
}
