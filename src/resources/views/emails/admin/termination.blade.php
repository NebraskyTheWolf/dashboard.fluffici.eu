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
                                        <img src="{{ url('/icons/alert-octagon.png') }}" class=" va-middle" width="40" height="40" alt="lock-open" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">Zrušení účtu</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center">
                            <p>Váš účet byl zrušen, protože jste nedodržoval(a) naše <strong>Pravidla</strong>. Obraťte se prosím na svého vyššího administrátora.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
