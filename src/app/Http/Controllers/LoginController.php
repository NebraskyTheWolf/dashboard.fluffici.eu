<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Orchid\Platform\Models\User;

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
     * Handle a login request to the application.
     *
     *
     * @throws ValidationException
     *
     * @return JsonResponse|RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $auth = $this->guard->attempt(
            $request->only(['email', 'password']),
            $request->filled('remember')
        );

        if ($auth) {
            if (env('APP_OTP', false)) {
                $otp = new UserOtp();
                $otp->user_id = $auth->id;
                $otp->token = $this->generateNumericToken(8);
                $otp->expiry = Carbon::now()->addMinutes(30);
                $otp->save();

                Mail::to($request->input('email'))->send(new UserOtpMail(User::where('id', $auth->id)->first(), $otp->token));

                return view('auth.otp');
            } else {
                $this->sendLoginResponse($auth);
            }
        }

        throw ValidationException::withMessages([
            'email' => __('The details you entered did not match our records. Please double-check and try again.'),
        ]);
    }

    public function otp(Request $request)
    {
        $request->validate([
            'otp'    => 'required|integer'
        ]);

        $otp = UserOtp::where('token', $request->input('otp'));

        if ($otp->exists()) {
            $data = $otp->first();
            $user = User::where('id', $data->user_id)->first();

            $auth = $this->guard->attempt([
                'email' => $user->email,
                'password' => $user->password
            ], true);

            $otp->delete();

            $this->sendLoginResponse($auth);
        }

        throw ValidationException::withMessages([
            'otp' => "The OTP token you entered is invalid.",
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended("main");
    }

    /**
     * @param Guard $guard
     *
     * @return Factory|View
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
     * @return RedirectResponse
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
        Impersonation::logout();

        return redirect()->route("index");
    }

    /**
     * Log the user out of the application.
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
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
