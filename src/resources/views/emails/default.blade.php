@extends('emails.base-gmail')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; border-radius: 4px; -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); border: 1px solid #dce0e5;" bgcolor="#ffffff">
        <tr>
            <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                <table cellpadding="0" cellspacing="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                    <tr>
                        <td class="content pb-0" align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px 0;">
                            <table class="icon-lg" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 72px; height: 72px; font-size: 48px;">
                                <tr>
                                    <td valign="middle" align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                                        <img src="{{ url('/icons/message.png') }}" class=" va-middle" width="40" height="40" alt="message" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: middle; font-size: 0; width: 40px; height: 40px; border-style: none; border-width: 0;" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md" style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 16px 0 0;" align="center">New message</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
                            <p style="margin: 0 0 1em;">Hi,</p>
                            <p style="margin: 0 0 1em;"><a href="" style="color: #206bc4; text-decoration: none;">{{ $assignee }}</a> sent you a message from <strong style="font-weight: 600;">Fluffici z.s</strong>:</p>
                            <table class="my-xl" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; margin-top: 48px; margin-bottom: 48px;">
                                <tr>
                                    <td class="w-1p va-top pr-md" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; width: 1%; padding-right: 16px;" valign="top">
                                        <img src="{{ $avatarURL }}" class=" avatar " width="48" height="48" alt="" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: baseline; font-size: 0; border-radius: 50%; -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); border-style: none; border-width: 0;" />
                                    </td>
                                    <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                                        <div class="quote" style="background-color: #fafafa; border-radius: 4px; display: inline-block; padding: 8px 12px;">
                                            <div class="font-strong" style="font-weight: 600;">{{ $assignee }} says:</div>
                                            {!! $content !!}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
