@extends('emails.base-gmail')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; border-radius: 4px; -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); border: 1px solid #dce0e5;" bgcolor="#ffffff">
        <tr>
            <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                <table cellpadding="0" cellspacing="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                    <tr>
                        <td class="content text-center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;" align="center">
                            <div>
                                <img src="{{ url('/illustrations/undraw_gdpr_3xfb.png') }}" alt="" height="160" class="img-illustration" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: baseline; font-size: 0; max-width: 240px; max-height: 160px; width: auto; height: auto; border-style: none; border-width: 0;" />
                            </div>
                            <h1 class="mb-0 mt-lg" style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 24px 0 0;">We're updating our GDPR terms</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 0 48px 40px;">
                            <p style="margin: 0 0 1em;">We are contacting you as the billing contact or technical contact for Fluffici, z.s. product.</p>
                            <p style="margin: 0 0 1em;">We want to let you know that we are retiring the Fluffici, z.s. Customer Agreement and replacing it with new
                                legal
                                terms that are specific to deployment type.</p>
                            <p style="margin: 0 0 1em;">Here are some of the key changes:</p>
                            <ul style="margin: 0 0 1em;">
                                {!! $content !!}
                            </ul>
                            <p style="margin: 0 0 1em;">Thank you for being Fluffici, z.s. customer!</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 0 48px 40px;">
                            <table cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                <tr>
                                    <td class="va-top pr-md w-1p" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; width: 1%; padding-right: 16px;" valign="top">
                                        <img src="{{ $author->getImage() }}" class=" avatar " width="48" height="48" alt="" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: baseline; font-size: 0; border-radius: 50%; -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); border-style: none; border-width: 0;" />
                                    </td>
                                    <td class="va-middle" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" valign="middle">
                                        {{ $author->getName() }}<br />
                                        <span class="text-muted" style="color: #667382;">{{ $author->getRole() }}</span>
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
