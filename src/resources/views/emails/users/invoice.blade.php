@extends('emails.base-gmail')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; border-radius: 4px; -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); color: #dddddd; border: 1px solid #2B3648;" bgcolor="#2B3648">
        <tr>
            <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                <table cellpadding="0" cellspacing="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                    <tr>
                        <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
                            <table class="row" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td class="col va-middle text-mobile-center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" valign="middle">
                                        <h1 class="m-0" style="font-weight: 600; color: #dddddd; font-size: 28px; line-height: 130%; margin: 0;">Invoice</h1>
                                    </td>
                                    <td class="col-spacer" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; width: 24px;" valign="top"></td>
                                    <td class="col va-middle text-mobile-center text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" align="right" valign="middle">
                                        <a href="https://www.fluffici.eu" style="color: #206bc4; text-decoration: none;"><img src="https://autumn.fluffici.eu/attachments/jVrNMLSH1BNA5ZnqGhpLGhVkFoteCwM_Lq0Y5G9Ij7" width="116" height="34" alt="" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: baseline; font-size: 0; border-style: none; border-width: 0;" /></a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 0 48px 40px;">
                            <table class="row" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td class="col py-lg" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding-top: 24px; padding-bottom: 24px;" valign="top">
                                        <h4 style="font-weight: 600; color: #dddddd; font-size: 16px; margin: 0 0 0.5em;">Bill to</h4>
                                        <p style="margin: 0 0 1em;">
                                            {{ $order->first_name }} {{ $order->last_name }}<br />
                                            {{ $order->email }}<br />
                                            {{ $order->phone_number }}
                                        </p>
                                        <p class="mb-0" style="margin: 0;">
                                            {{ $order->first_address }}<br />
                                            {{ $order->second_address }}<br />
                                            {{ $order->postal_code }}<br />
                                            {{ $order->country }}
                                        </p>
                                    </td>
                                    <td class="col-spacer" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; width: 24px;" valign="top"></td>
                                    <td class="col" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" valign="top">
                                        <table cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                            <tr>
                                                <td class="rounded p-lg bg-light" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-radius: 4px; padding: 24px;" bgcolor="#354258">
                                                    <h4 style="font-weight: 600; color: #dddddd; font-size: 16px; margin: 0 0 0.5em;">Invoice details</h4>
                                                    <p class="mb-0" style="margin: 0;">
                                                        Invoice Date: {{ $invoiceDate }}<br />
                                                        Payment Due: {{ $invoiceDate }}<br />
                                                        <br />
                                                        <strong style="font-weight: 600;">Amount Due: {{ $totalDue }}</strong>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
                            <h4 style="font-weight: 600; color: #dddddd; font-size: 16px; margin: 0 0 0.5em;">Your order</h4>
                            <table class="table" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                <tr>
                                    <th colspan="2" style="text-transform: uppercase; font-weight: 600; color: #667382; font-size: 12px; padding: 0 0 4px;"></th>
                                    <th style="text-transform: uppercase; font-weight: 600; color: #667382; font-size: 12px; padding: 0 0 4px;">Qty</th>
                                    <th class="text-right" style="text-transform: uppercase; font-weight: 600; color: #667382; font-size: 12px; padding: 0 0 4px;" align="right">Price</th>
                                </tr>

                                @foreach($products as $product)
                                    <tr>
                                        <td class="pr-0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">
                                            <a href="https://shop.fluffici.eu/checkout/{{ $product->id }}" style="color: #206bc4; text-decoration: none;">
                                                <img src="{{ $product->getImage() }}" class=" rounded" width="64" height="64" alt="" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: baseline; font-size: 0; border-radius: 4px; border-style: none; border-width: 0;" />
                                            </a>
                                        </td>
                                        <td class="pl-md w-100p" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; width: 100%; padding: 4px 12px;">
                                            <strong style="font-weight: 600;">{{ $product->name }}</strong><br />
                                            <span class="text-muted" style="color: #667382;">{{ $product->description }}</span>
                                        </td>
                                        <td class="text-center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px;" align="center">1</td>
                                        <td class="text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 0 4px 12px;" align="right">{{ $product->getNormalizedPrice() }}</td>
                                    </tr>
                                @endforeach


                                <tr>
                                    <td colspan="3" class="border-top text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-top-width: 1px; border-top-style: solid; padding: 4px 12px 4px 0; border-color: #3E495B;" align="right">Subtotal</td>
                                    <td class="border-top text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-top-width: 1px; border-top-style: solid; padding: 4px 0 4px 12px; border-color: #3E495B;" align="right">{{ $subTotal }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;" align="right">Shipping</td>
                                    <td class="text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 0 4px 12px;" align="right">{{ $carrierPrice }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;" align="right">Tax</td>
                                    <td class="text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 0 4px 12px;" align="right">{{ $tax }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right font-strong h3 m-0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; color: #dddddd; font-size: 20px; line-height: 130%; margin: 0; padding: 4px 12px 4px 0;" align="right">Total</td>
                                    <td class="font-strong h3 m-0 text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; color: #dddddd; font-size: 20px; line-height: 130%; margin: 0; padding: 4px 0 4px 12px;" align="right">{{ $totalDue }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
                            <table class="row" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td class="col" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" valign="top">
                                        <table cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                            <tr>
                                                <td align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: separate; width: 100%; color: #ffffff; border-radius: 4px;" bgcolor="#206bc4">
                                                        <tr>
                                                            <td align="center" valign="top" class="lh-1" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; line-height: 100%;">
                                                                <a href="https://shop.fluffici.eu/api/invoices?invoiceId={{ $invoiceId }}" class="btn bg-blue border-blue" style="color: #ffffff; text-decoration: none; white-space: nowrap; font-weight: 500; font-size: 16px; border-radius: 4px; line-height: 100%; display: block; -webkit-transition: 0.3s background-color; -o-transition: 0.3s background-color; transition: 0.3s background-color; background-color: #206bc4; padding: 12px 32px; border: 1px solid #206bc4;">
                                                                    <span class="btn-span" style="color: #ffffff; font-size: 16px; text-decoration: none; white-space: nowrap; font-weight: 500; line-height: 100%;">Print invoice</span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="col-spacer" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; width: 24px;" valign="top"></td>
                                    <td class="col" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" valign="top">
                                        <table cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                            <tr>
                                                <td align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="bg-secondary rounded" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: separate; width: 100%; color: #ffffff; border-radius: 4px;" bgcolor="transparent">
                                                        <tr>
                                                            <td align="center" valign="top" class="lh-1" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; line-height: 100%;">
                                                                <a href="https://shop.fluffici.eu/support" class="btn bg-secondary border-secondary" style="color: #667382; text-decoration: none; white-space: nowrap; font-weight: 500; font-size: 16px; border-radius: 4px; line-height: 100%; display: block; -webkit-transition: 0.3s background-color; -o-transition: 0.3s background-color; transition: 0.3s background-color; background-color: transparent; padding: 12px 32px; border: 1px solid #3E495B;">
                                                                    <span class="btn-span" style="color: #667382; font-size: 16px; text-decoration: none; white-space: nowrap; font-weight: 500; line-height: 100%;">Contact us</span>
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
            </td>
        </tr>
    </table>
@endsection
