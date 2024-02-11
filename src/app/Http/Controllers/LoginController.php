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

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        if (env('APP_OTP', false)) {
            $user = User::where('email', $request->input('email'));
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

    public function challenge(Request $request)
    {
        return view('auth.otp');
    }

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

    public function password(Request $request)
    {
        return view('auth.recovery', [
            'token' => $request->token
        ]);
    }

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
