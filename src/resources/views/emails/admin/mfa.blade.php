@extends('emails.base')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content">
                            <p>Hi, <strong>{{ $user->name }}</strong>!</p>
                            <p>It looks like you are trying to log in to {{ env('APP_NAME') }} using your username and password. As an additional security measure you are requested to enter the OTP code (one-time password) provided in this email.</p>
                            <p>If you did not intend to log in to {{ env('APP_NAME') }}, please ignore this email.</p>
                            <p class="mb-0">The OTP code is:</p>
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
                            <p>If you enable two-factor authentication (2FA) with a trusted device you will not be asked for an OTP over email anymore.</p>
                            <p>2FA is an extra layer of security used when logging into websites or apps. With 2FA, you have to log in with your username and password and provide another form of authentication that only you know or have access to.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
