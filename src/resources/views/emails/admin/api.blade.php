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
                                        <img src="{{ url('/icons/api.png') }}" class=" va-middle" width="40" height="40" alt="lock-open" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">API Login</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center">
                            <p>You receive this email because you logged on via the API at {{ \Carbon\Carbon::now() }} if this was made on your behalf please lock down the API access on your account settings!</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
