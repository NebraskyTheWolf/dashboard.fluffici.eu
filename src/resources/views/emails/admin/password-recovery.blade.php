@extends('emails.base')

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
                                        <img src="{{ url('/icons/lock-open.png') }}" class=" va-middle" width="40" height="40" alt="lock-open" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">Instrukce pro Obnovení Hesla</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center">
                            <p>Na účtu Fluffici jste nedávno požadovali resetování hesla. Použijte tlačítko níže k jeho resetování. Tato zpráva vyprší za 24 hodin.</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center pt-0 pb-xl">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded w-auto">
                                            <tr>
                                                <td align="center" valign="top" class="lh-1">
                                                    <a href="{{ url(route('api.login.recovery')) }}?token={{ $token }}" class="btn bg-blue border-blue">
                                                        <span class="btn-span">Resetovat&nbsp;heslo</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-muted pt-0 text-center">
                            Máte potíže s výše uvedeným tlačítkem? Zkopírujte prosím tuto URL: <a href="{{ url(route('api.login.recovery')) }}?token={{ $token }}">{{ url(route('api.login.recovery')) }}?token={{ $token }}</a> a vložte ji do svého prohlížeče. Pokud jste nežádali o resetování hesla, ignorujte prosím tuto zprávu.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
