<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\PasswordRecovery;
use App\Mail\UserOtpMail;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\Factory;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Orchid\Platform\Models\User;
use Random\RandomException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * @var Guard|\Illuminate\Auth\SessionGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     */
    public function __construct(Auth $auth)
    {
        $this->guard = $auth->guard(config('platform.guard'));

        $this->middleware('guest', [
            'except' => [
                'logout',
                'switchLogout',
            ],
        ]);
    }

    /**
     * Login user and redirect to appropriate route
     *
     * @param Request $request
     * The incoming request
     *
     * @return RedirectResponse|void
     * A redirect response or void
     * @throws ValidationException
     * If validation fails
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'));
        if ($user->exists()) {
            $data = $user->first();
            if ($data->isTerminated()) {
                throw ValidationException::withMessages([
                    'email' => 'Your account was terminated.',
                ]);
            }
        }

        if (env('APP_OTP', false)) {
            if ($user->exists()) {
                $data = $user->first();

                if (Hash::check($request->input('password'), $data->password)) {

                    $otp = new UserOtp();
                    $otp->user_id = $data->id;
                    $otp->token = $this->generateNumericToken(8);
                    $otp->expiry = Carbon::now()->addMinutes(30);
                    $otp->save();

                    Mail::to($data->email)->send(new UserOtpMail($data, $otp->token));

                    return redirect()->route('login.challenge');
                }
            }
        } else {
            $auth = $this->guard->attempt(
                $request->only(['email', 'password']),
                $request->filled('remember')
            );

            $this->sendLoginResponse($auth);
        }

        throw ValidationException::withMessages([
            'email' => 'The details you entered did not match our records. Please double-check and try again.',
        ]);
    }

    /**
     * Generate and show one-time password (OTP) view.
     *
     * @param Request $request The HTTP request object.
     *
     * @return Factory|View The generated view object.
     */
    public function challenge(Request $request)
    {
        return view('auth.otp');
    }

    /**
     * Validate and process the OTP token.
     *
     * @param Request $request The HTTP request instance.
     *
     * @return JsonResponse|RedirectResponse The response containing JSON data or a redirect response.
     *
     * @throws ValidationException If the OTP token is invalid.
     */
    public function otp(Request $request)
    {
        $request->validate([
            'otp'    => 'required'
        ]);

        $otp = UserOtp::where('token', $request->input('otp'));

        if ($otp->exists()) {
            $data = $otp->first();
            $user = User::where('id', $data->user_id)->first();

            \Illuminate\Support\Facades\Auth::guard('web')->login($user, true);

            $otp->delete();
            $request->session()->regenerate();

            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect()->intended("main");
        }

        throw ValidationException::withMessages([
            'otp' => "The OTP token you entered is invalid.",
        ]);
    }

    /**
     * Send the login response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended("main");
    }

    /**
     * Show the login form.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Contracts\View\View The login form view.
     */
    public function showLoginForm(Request $request)
    {
        $user = $request->cookie('lockUser');

        /** @var EloquentUserProvider $provider */
        $provider = $this->guard->getProvider();

        $model = $provider->createModel()->find($user);

        return view('auth.login', [
            'isLockUser' => optional($model)->exists ?? false,
            'lockUser'   => $model,
        ]);
    }

    /**
     * Reset the LockMe cookie.
     *
     * @param CookieJar $cookieJar The CookieJar instance for managing cookies.
     *
     * @return \Illuminate\Http\RedirectResponse The redirect response to the login page with the updated cookie.
     */
    public function resetCookieLockMe(CookieJar $cookieJar)
    {
        $lockUser = $cookieJar->forget('lockUser');

        return redirect()->route('login')->withCookie($lockUser);
    }

    /**
     * @return RedirectResponse
     */
    public function switchLogout()
    {
        return redirect()->route("index");
    }

    /**
     * Logout the authenticated user.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse The JSON response if the request wants JSON, otherwise the redirect response to the homepage.
     */
    public function logout(Request $request)
    {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * Display the password recovery form.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Contracts\View\View The password recovery form view.
     */
    public function password(Request $request)
    {
        return view('auth.recovery', [
            'token' => $request->token
        ]);

    }

    /**
     * Perform password recovery.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\RedirectResponse The login form redirect response on success, or throws a ValidationException on failure.
     */
    public function recovery(Request $request)
    {
        $request->validate([
            'new_password'    => 'required|string',
            'token' => 'required|string'
        ]);

        $token = PasswordRecovery::where('token', $request->input('token'));
        if ($token->exists()) {
            $data = $token->first();
            User::where('id', $data->user_id)->update([
                'password' => Hash::make($request->input('new_password'))
            ]);
            $data->delete();

            return redirect()->route('login');
        }

        throw ValidationException::withMessages([
            'new_password' => "Your password recovery token is invalid.",
        ]);
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
