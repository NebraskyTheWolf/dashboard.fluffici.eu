@extends('emails.base' )

@section('content')
    <table class="box" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content pb-0" align="center">
                            <table class="icon-lg" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td valign="middle" align="center">
                                        <img src="{{ url('/icons/2fa.png') }}" class=" va-middle" width="40" height="40" alt="lock-open" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">Instrukce pro OTP</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <p>Ahoj, <strong>{{ $user->name }}</strong>!</p>
                            <p>Zdá se, že se snažíte přihlásit do {{ env('APP_NAME') }} pomocí svého uživatelského jména a hesla. Jako dodatečné bezpečnostní opatření se od vás požaduje, abyste zadali kód OTP (jednorázové heslo) uvedené v tomto e-mailu.</p>
                            <p>Pokud jste neměli v úmyslu přihlásit se k {{ env('APP_NAME') }}, prosím, ignorujte tento e-mail.</p>
                            <p class="mb-0">Kód OTP je:</p>
                            <table>
                                <tr>
                                    <td class="py-lg">
                                        <table cellspacing="0" cellpadding="0" class="w-auto" align="center">
                                            <tr>
                                                <td class="border-dashed border-wide border-dark text-center rounded px-lg py-md">
                                                    <div class="h1 font-strong m-0">{{ $otpToken }}</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <p>Pokud aktivujete ověření ve dvou fázové (2FA) s důvěryhodným zařízením, již vám nebude zasílán e-mail s OTP.</p>
                            <p>2FA je další vrstva zabezpečení používaná při přihlašování na webové stránky nebo do aplikací. S 2FA musíte zadat své uživatelské jméno a heslo a poskytnout další formu ověření, kterou jen vy znáte nebo k ní máte přístup.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
