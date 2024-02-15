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
                                        <img src="{{ url('/icons/' . $icon . '.png') }}" class=" va-middle" width="40" height="40" alt="truck" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: middle; font-size: 0; width: 40px; height: 40px; border-style: none; border-width: 0;" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md" style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 16px 0 0;" align="center">{{ $title }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
                            <p style="margin: 0 0 1em;">Hi {{ $order->first_name }}, <br />
                                {{ $message }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 0 48px 40px;">
                            <table cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                <tr>
                                    <td align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                                        <table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded w-auto" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: separate; width: auto; color: #ffffff; border-radius: 4px;" bgcolor="#206bc4">
                                            <tr>
                                                <td align="center" valign="top" class="lh-1" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; line-height: 100%;">
                                                    <a href="https://shop.fluffici.eu/order/track/{{ $order->order_id }}" class="btn bg-blue border-blue" style="color: #ffffff; text-decoration: none; white-space: nowrap; font-weight: 500; font-size: 16px; border-radius: 4px; line-height: 100%; display: block; -webkit-transition: 0.3s background-color; -o-transition: 0.3s background-color; transition: 0.3s background-color; background-color: #206bc4; padding: 12px 32px; border: 1px solid #206bc4;">
                                                        <span class="btn-span" style="color: #ffffff; font-size: 16px; text-decoration: none; white-space: nowrap; font-weight: 500; line-height: 100%;">Track your order</span>
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
