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
                                        <img src="{{ url('/icons/gift.png') }}" class=" va-middle" width="40" height="40" alt="gift" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: middle; font-size: 0; width: 40px; height: 40px; border-style: none; border-width: 0;" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;" align="center">
                            <h1 style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 0 0 0.5em;"><strong style="font-weight: 600;">{{ $percentage }}%</strong> Off on {{ $productName }}</h1>
                            <p class="text-muted mb-0" style="color: #667382; margin: 0;">{{ $message }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
                            <table cellspacing="0" cellpadding="0" class="w-auto" align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: auto;">
                                <tr>
                                    <td class="border-dashed border-wide border-dark text-center rounded px-lg py-md" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-radius: 4px; padding: 16px 24px; border: 2px dashed #d1d1d1;" align="center">
                                        <div class="h1 font-strong m-0" style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 0;">{{ $promoCode }}</div>
                                        <div class="text-muted" style="color: #667382;">Expires on {{ $expiry->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
                            <table cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                <tr>
                                    <td align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                                        <table cellpadding="0" cellspacing="0" border="0" class="bg-green rounded w-auto" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: separate; width: auto; color: #ffffff; border-radius: 4px;" bgcolor="#2fb344">
                                            <tr>
                                                <td align="center" valign="top" class="lh-1" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; line-height: 100%;">
                                                    <a href="https://shop.fluffici.eu" class="btn bg-green border-green" style="color: #ffffff; text-decoration: none; white-space: nowrap; font-weight: 500; font-size: 16px; border-radius: 4px; line-height: 100%; display: block; -webkit-transition: 0.3s background-color; -o-transition: 0.3s background-color; transition: 0.3s background-color; background-color: #2fb344; padding: 12px 32px; border: 1px solid #2fb344;">
                                                        <span class="btn-span" style="color: #ffffff; font-size: 16px; text-decoration: none; white-space: nowrap; font-weight: 500; line-height: 100%;">Go to shop now</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
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
