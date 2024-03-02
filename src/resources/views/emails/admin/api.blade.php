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
                                        <img src="{{ url('/icons/api.png') }}" class=" va-middle" width="40" height="40" alt="zamknění-otevřeno" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">API Přihlášení</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center">
                            <p>Tento email dostáváte, protože jste se přihlásili prostřednictvím API v {{ \Carbon\Carbon::now() }} pokud to bylo provedeno za vás, prosím zablokujte přístup k API ve vašem nastavení účtu!</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
