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
                            <h1 class="text-center m-0 mt-md">Reset Password Instruction</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center">
                            <p>You recently requested to reset a password for your on your {{ env('APP_NAME') }} account. Use the button below to reset it. This message will expire in 24 hours.</p>
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
                                                    <a href="{{ url(route('api.login.recovery', $token)) }}" class="btn bg-blue border-blue">
                                                        <span class="btn-span">Reset&nbsp;password</span>
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
                            Having trouble with the button above? Please copy this URL: <a href="{{ url(route('api.login.recovery', $token)) }}">{{ url(route('api.login.recovery', $token)) }}</a> and paste it into your browser. If you didn't request a password reset, please ignore this message.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
