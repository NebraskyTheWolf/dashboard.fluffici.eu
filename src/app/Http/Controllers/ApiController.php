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
 * Třída ApiController
 *
 * Tato třída zpracovává požadavky API pro ověření uživatele.
 * Poskytuje metody pro přihlášení uživatele, ověření OTP a získání informací o produktech.
 */
class ApiController extends Controller
{
    /**
     * Metoda Index
     *
     * Na základě poskytnutého uživatelského jména a hesla získává informace o uživateli.
     * Pokud uživatel existuje a heslo je správné, přihlášení je úspěšné
     * a je vrácena odpověď s tokenem a zprávou o úspěchu.
     * Pokud je účet uživatele ukončen, je vrácena odpověď s chybovou zprávou o omezení účtu.
     * Pokud uživatel neexistuje nebo je heslo nesprávné, je vrácena chybová zpráva o chybných přihlašovacích údajích.
     *
     * @param Request $request Objekt požadavku obsahující JSON data s uživatelským jménem a heslem.
     * @return JsonResponse JSON objekt odpovědi obsahující stav přihlášení, token, chybu (pokud existuje) a zprávu.
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->validateInput($request);
        if ($data === false) {
            return $this->createErrorResponse('CREDENTIALS', 'Email nebo heslo je neplatné.');
        }

        $user = $this->findByUsername($data['email']);
        if ($user === null) {
            return $this->createErrorResponse('CREDENTIALS', 'Email nebo heslo je neplatné.');
        }

        if (!$this->validatePassword($data['password'], $user->password)) {
            return $this->createErrorResponse('CREDENTIALS', 'Email nebo heslo je neplatné.');
        }

        if ($user->isTerminated()) {
            return $this->createErrorResponse('ACCOUNT_RESTRICTED', 'Váš účet je zrušeni.');
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
     * Ověřuje kód OTP (One-Time Password) poskytnutý uživatelem.
     *
     * @param Request $request Objekt HTTP požadavku.
     * @return JsonResponse Odpověď indikující výsledek ověření OTP.
     *                       Pokud je poskytnutý kód OTP platný, odpověď bude obsahovat
     *                       stav úspěchu a uživatelský token.
     *                       Pokud je poskytnutý kód OTP neplatný, odpověď bude obsahovat
     *                       stav chyby a chybovou zprávu.
     *                       Chybová zpráva bude "Váš OTP kód je neplatný.".
     *                       Stav chyby bude "CREDENTIALS".
     *                       Pokud se během procesu ověření OTP vyskytne chyba,
     *                       bude vrácena chybová odpověď se stavem "INVALID_REQUEST" a zprávou
     *                       "Ověření OTP kódu se nezdařilo.".
     */
    public function validateOtp(Request $request): JsonResponse
    {
        $data = $this->validateOtpInput($request);
        if ($data === false) {
            return $this->createErrorResponse('CREDENTIALS', 'Váš OTP kód je neplatný.');
        }

        $otp = UserOtp::where('token', $data['code']);

        if ($otp->exists()) {
            $data = $otp->first();
            $user = User::where('id', $data->user_id)->first();
            $data->delete();

            $this->sendNotification($user);

            return $this->createSuccessResponse($user->createUserToken(), $user);
        }

        return response()->json([
            'status' => false,
            'error' => 'INVALID_REQUEST',
            'message' => 'Ověření OTP kódu se nezdařilo.'
        ]);
    }


    /**
     * Ověřuje vstup z požadavku.
     *
     * @param Request $request Objekt požadavku obsahující vstupní data.
     *
     * @return array|bool Ověřená vstupní data, pokud projdou ověřením, nebo false, pokud některé požadované položky chybí.
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
     * Ověřuje vstup OTP z požadavku.
     *
     * @param Request $request HTTP objekt požadavku.
     *                         Objekt požadavku se používá ke získání vstupních dat.
     *
     * @return bool|array  Vrací buď boolean hodnotu nebo asociativní pole.
     *                      Ketliže v datech požadavku je pole 'code' prázdné,
     *                      vrátí se false k indikaci, že vstup OTP je neplatný.
     *                     Jinak se vrací data požadavku jako asociativní pole.
     *                     Pole obsahuje vstupní data z požadavku.
     *                     Formát pole je stejný jako původní data požadavku.
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
     * Najde uživatele podle daného uživatelského jména.
     *
     * @param string $email Uživatelské jméno uživatele k nalezení.
     *
     * @return User|null Uživatelský objekt, pokud byl nalezen, jinak null.
     */
    private function findByUsername(string $email): ?User
    {
        $user = User::where('email', $email);

        return $user->exists() ? $user->first() : null;
    }

    /**
     * Ověří heslo proti heslu uživatele.
     *
     * @param string $inputPassword Heslo, které se má ověřit.
     * @param string $userPassword Uživatelské heslo, se kterým se má porovnat.
     *
     * @return bool True, pokud je heslo platné, jinak false.
     */
    private function validatePassword(string $inputPassword, string $userPassword): bool
    {
        return Hash::check($inputPassword, $userPassword);
    }

    /**
     * Pošle notifikaci na daný email uživatele.
     *
     * @param User $user Uživatel, kterému se má notifikace poslat.
     *
     * @return void
     */
    private function sendNotification(User $user): void
    {
        Mail::to($user->email)->send(new UserApiNotification());
    }

    /**
     * Vytváří odpověď s chybou s danou chybou a zprávou.
     *
     * @param string $error Kód chyby nebo zprávy, který má být zahrnut do odpovědi.
     * @param string $message Popis chyby nebo další zpráva, která má být zahrnuta do odpovědi.
     *
     * @return JsonResponse JSON odpověď indikující, že došlo k chybě.
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
     * Vytvoří odpověď s úspěchem s daným tokenem.
     *
     * @param string $token Token, který má být zahrnut do odpovědi.
     *
     * @return JsonResponse  JSON odpověď indikující úspěšné přihlášení.
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
            'message' => 'Přihlášení bylo úspěšné.'
        ]);
    }

    /**
     * Získá kód EAN pro dané ID produktu.
     *
     * @param Request $request HTTP objekt požadavku.
     *                         METODA query() se používá k získání parametrů z požadavku.
     *                         Parametr 'productId' je vyžadován.
     *
     * @return JsonResponse|BinaryFileResponse  Kód EAN pro dané ID produktu, nebo chybová odpověď pokud je ID produktu chybí.
     *               Chybová odpověď je ve formě asociativního pole s klíči 'error_code' a 'error_message'.
     *               Pokud je ID produktu chybí, kód chyby bude "MISSING_PRODUCT_ID" a chybová zpráva
     *               bude "Chybí productId".
     *               Pokud je ID produktu nalezeno, kód EAN bude vrácen jako řetězec.
     */
    public function fetchEANCode(Request $request): JsonResponse|BinaryFileResponse
    {
        $productId = $request->query('productId');
        if ($productId == null) {
            return $this->createErrorResponse("MISSING_PRODUCT_ID", "Chybí productId");
        }


        $response = \Httpful\Request::post(env("IMAGER_HOST", 'http://185.188.249.234:3900/product/'), [
            'productId' => $productId
        ], "application/json")->expectsJson()->send();

        if ($response->code == 200) {
            return $this->fetchProductImage($productId);
        } else {
            return response()->json([
                'error' => 'Server neodpovídal správně.'
            ]);
        }
    }

    /**
     * Získá obrázek produktu pro daný produkt.
     *
     * @param string $productId ID produktu.
     *
     * @return JsonResponse|BinaryFileResponse Obrázek produktu v případě, že existuje, nebo JSON odpověď s chybovou zprávou pokud neexistuje.
     *   - Pokud soubor obrázku produktu existuje v úložišti, bude stažen.
     *   - Pokud soubor obrázku produktu neexistuje v úložišti, vrátí se JSON odpověď:
     *     - chyba: Nenalezeno v úložišti.
     */
    private function fetchProductImage(string $productId): JsonResponse|BinaryFileResponse
    {
        $storage = Storage::disk('public');
        if ($storage->exists($productId . '-code128.png')) {
            return response()->download(storage_path('app/public/' . $productId . '-code128.png'));
        } else {
            return $this->createErrorResponse("FILE_NOT_FOUND", "Nenalezeno v úložišti.");
        }
    }

    /**
     * Vygeneruje číselný token.
     *
     * @param int $length Délka tokenu (výchozí: 4).
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
}
