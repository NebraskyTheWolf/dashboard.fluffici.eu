@extends('emails.base-gmail')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; border-radius: 4px; -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); border: 1px solid #dce0e5;" bgcolor="#ffffff">
        <tr>
            <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                <table cellpadding="0" cellspacing="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                    <tr>
                        <td class="content text-center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;" align="center">
                            <div>
                                <img src="{{ url('/illustrations/security.png') }}" alt="" height="160" class="img-illustration" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: baseline; font-size: 0; max-width: 240px; max-height: 160px; width: auto; height: auto; border-style: none; border-width: 0;" />
                            </div>
                            <h1 class="mb-0 mt-lg" style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 24px 0 0;">{{ __('termination.title') }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 0 48px 40px;">
                            <p style="margin: 0 0 1em;">{{ __('termination.hello') }}</p>
                            <ul style="margin: 0 0 1em;">
                                <li><strong style="font-weight: 600;">Fluffici</strong> {{ __('termination.synopsis.one') }}
                                    {{ __('termination.synopsis.two') }}
                                    {!! __('termination.synopsis.three') !!}
                                </li>
                                <li>
                                    {{ __('termination.desc.one') }}
                                    {!! __('termination.desc.two') !!}
                                </li>
                            </ul>
                            <p style="margin: 0 0 1em;">{{ __('termination.greetings.title') }}</p>
                            <p style="margin: 0 0 1em;">{{ __('termination.greetings.signature') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
