<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\PasswordRecovery;
use App\Mail\UserOtpMail;
use App\Models\Security\Auth\UserOtp;
use App\Models\Security\OAuth\OTPRequest;
use Carbon\Carbon;
use Coderflex\LaravelTurnstile\Rules\TurnstileCheck;
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
use Orchid\Support\Facades\Toast;
use Ramsey\Uuid\Uuid;
use Random\RandomException;

class LoginController extends Controller
{

    public array $allowed = [
        'fluffici.eu'
    ];

    /*
    |--------------------------------------------------------------------------
    | Řadič Přihlášení
    |--------------------------------------------------------------------------
    |
    | Tento řadič zajišťuje ověřování uživatelů pro aplikaci a
    | přesměrování je na domovskou obrazovku. Řadič používá trait
    | pro pohodlné poskytnutí svých funkcí vašim aplikacím.
    |
    */

    /**
     * @var Guard|\Illuminate\Auth\SessionGuard
     */
    protected $guard;

    /**
     * Vytvoření nové instance řadiče.
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
     * Přihlásit uživatele a přesměrovat na vhodnou trasu
     *
     * @param Request $request
     * Příchozí požadavek
     *
     * Přesměrování nebo void
     * @throws ValidationException
     * Pokud validace selže
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
            'cf-turnstile-response' => ['required', new TurnstileCheck()],
        ]);

        // List of allowed domains


        if (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $parts = explode('@', $request->input('email'));
            $domain = array_pop($parts);

            if (!in_array($domain, $this->allowed)) {
                return throw ValidationException::withMessages([
                    'email' => 'Zadané údaje se neshodovaly s našimi záznamy. Zkontrolujte je a zkuste to znovu.',
                ]);
            }
        }

        $user = User::where('email', $request->input('email'));
        if ($user->exists()) {
            $user = $user->first();
            if ($user->isTerminated()) {
                return throw ValidationException::withMessages([
                    'email' => 'Váš účet byl ukončen.',
                ]);
            }

            if ($user->hasAccess('platform.systems.dashboard')) {
                if (Hash::check($request->input('password'), $user->password)) {
                    if ($user->is_fcm == 1) {
                        $reqId = Uuid::uuid4()->toString();

                        $otpRequest = new OTPRequest();
                        $otpRequest->user_id = $user->id;
                        $otpRequest->requestId = $reqId;
                        $otpRequest->service = "dashboard.fluffici.eu";
                        $otpRequest->date = Carbon::now();
                        $otpRequest->ipAddress = '10.0.0.4';
                        $otpRequest->location = 'CZ';
                        $otpRequest->status = 'PENDING';
                        $otpRequest->save();

                        $user->sendFCMNotification('Login Request', 'You have one pending login request, click here to display the menu.');

                        return view('auth.otp')
                            ->with('isRequest', true)
                            ->with('requestId', $reqId);
                    } else {
                        $otp = new UserOtp();
                        $otp->user_id = $user->id;
                        $otp->token = $this->generateNumericToken(8);
                        $otp->expiry = Carbon::now()->addMinutes(30);
                        $otp->save();

                        Mail::to($user->email)->send(new UserOtpMail($user, $otp->token));
                    }

                    return redirect()->route('login.challenge');
                }
            } else {
                throw ValidationException::withMessages([
                    'email' => 'Permission denied.',
                ]);
            }
        }

        throw ValidationException::withMessages([
            'email' => 'Zadané údaje se neshodovaly s našimi záznamy. Zkontrolujte je a zkuste to znovu.',
        ]);
    }

    public function magicLogin(Request $request): RedirectResponse
    {
        $reqId = $request->query('requestId');
        $magicOTP = OTPRequest::where('requestId', $reqId)
            ->where('status', 'GRANTED');

        if ($magicOTP->exists()) {
            $magicOTP = $magicOTP->first();
            $magicOTP->status = 'USED';
            $magicOTP->save();

            $user = User::where('id', $magicOTP->user_id)->first();

            if ($user->hasAccess('platform.systems.dashboard')) {
                \Illuminate\Support\Facades\Auth::login($user, true);

                return redirect()->route('main');
            }
        }

        return redirect('/');
    }

    /**
     * Generovat a zobrazit zobrazení jednorázového hesla (OTP).
     *
     * @param Request $request HTTP požadavek.
     *
     * @return Factory|View Generovaný objekt zobrazení.
     */
    public function challenge(Request $request)
    {
        return view('auth.otp')
            ->with('isRequest', false)
            ->with('requestId', null);
    }

    /**
     * Ověřit a zpracovat token OTP.
     *
     * @param Request $request Instance HTTP požadavku.
     *
     * @return JsonResponse|RedirectResponse Odpověď obsahující data JSON nebo přesměrování.
     *
     * @throws ValidationException Pokud je token OTP neplatný.
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

            $request->user()->sendFCMNotification('New login detected.', 'You have logged on the dashboard at ' . Carbon::now()->format('D, d M Y H:i'));

            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect()->route("main");
        }

        throw ValidationException::withMessages([
            'otp' => "Zadaný token OTP je neplatný.",
        ]);
    }

    /**
     * Zobrazit formulář pro přihlášení.
     *
     * @param Request $request HTTP požadavek.
     *
     * @return \Illuminate\Contracts\View\View Formulář pro přihlášení.
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
     * Obnovte cookie LockMe.
     *
     * @param CookieJar $cookieJar Instance CookieJar pro správu cookie.
     *
     * @return \Illuminate\Http\RedirectResponse Přesměrování na stránku přihlášení s aktualizovanou cookie.
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
     * Odhlašte ověřeného uživatele.
     *
     * @param Request $request HTTP požadavek.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse JSON odpověď, pokud požadavek chce JSON, jinak přesměrování na hlavní stránku.
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
     * Zobrazte formulář pro obnovení hesla.
     *
     * @param Request $request Objekt HTTP požadavku.
     *
     * @return \Illuminate\Contracts\View\View Pohled na formulář pro obnovení hesla.
     */
    public function password(Request $request): \Illuminate\Contracts\View\View
    {
        return view('auth.recovery', [
            'token' => $request->query('token') ?? ''
        ]);
    }

    /**
     * Zobrazte formulář pro výzvu k obnovení.
     *
     * @param Request $request Objekt HTTP požadavku.
     *
     */
    public function recoveryChallenge(Request $request) {
        return view('auth.request_new_password', [
            'email', $request->query('email')
        ]);
    }

    public function recoveryChallengePost(Request $request): RedirectResponse
    {
        $request->validate([
            'cf-turnstile-response' => ['required', new TurnstileCheck()],
        ]);

        if ($request->has('email')) {
            $user = User::where('email', $request->input('email'));

            if ($user->exists()) {
                $user = $user->first();
                $this->generatePasswordResetCode($user);
            }

            Toast::success("Pokud je tento e-mail spojen s naší databází, brzy obdržíte e-mail.")
                ->disableAutoHide();

            return redirect()->route('login');
        }

        throw ValidationException::withMessages([
            'email' => "Zadejte prosím platný e-mail.",
        ]);
    }

    /**
     * Generování kódu pro obnovení hesla
     *
     * @param \app\Models\Security\Account\User $user
     *
     * @return void
     */
    public function generatePasswordResetCode(User $user): void
    {
        $token = Uuid::uuid4()->toString();

        // Uložte token do databáze spojené s uživatelem.
        $passwordRecovery = new \App\Models\Security\Auth\PasswordRecovery();
        $passwordRecovery->user_id = $user->id;
        $passwordRecovery->token = 1;
        $passwordRecovery->recovery_token = $token;
        $passwordRecovery->recovery_expiration = Carbon::now()->addHours(24)->toDateTime();
        $passwordRecovery->save();

        // Odešlete uživateli resetovací kód e-mailem.
        Mail::to($user->email)->send(new PasswordRecovery($token));
    }

    /**
     * Proveďte obnovení hesla.
     *
     * @param Request $request Objekt HTTP požadavku.
     *
     * @return \Illuminate\Http\RedirectResponse Přesměrování na formulář pro přihlášení v případě úspěchu, v případě selhání dojde k vyhození ValidationException.
     */
    public function recovery(Request $request)
    {
        $request->validate([
            'new_password'    => 'required|string',
            'token' => 'required|string',
            'cf-turnstile-response' => ['required', new TurnstileCheck()],
        ]);

        $token = \App\Models\Security\Auth\PasswordRecovery::where('recovery_token', $request->input('token'));
        if ($token->exists()) {
            $data = $token->first();
            $user = User::where('id', $data->user_id)->first();

            if ($user->isTerminated()) {
                throw ValidationException::withMessages([
                    'new_password' => 'Váš účet byl ukončen.',
                ]);
            }

            User::where('id', $data->user_id)->update([
                'password' => Hash::make($request->input('new_password'))
            ]);
            $data->delete();

            return redirect()->route('login');
        }

        throw ValidationException::withMessages([
            'new_password' => "Váš token pro obnovení hesla je neplatný.",
        ]);
    }

    /**
     * Generovat číselný token.
     *
     * @param int $length Délka tokenu (ve výchozím nastavení: 4).
     * @return string Vygenerovaný číselný token.
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

    private function splitString($str): string
    {
        $chunks = str_split($str, 6);
        foreach($chunks as &$chunk) {
            if(strlen($chunk) == 6) {
                $chunk = substr($chunk, 0, 3) . "-" . substr($chunk, 3);
            }
        }
        return implode(" ", $chunks);
    }
}
