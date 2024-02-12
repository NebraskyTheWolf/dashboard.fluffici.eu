@extends('emails.base-gmail')

@section('head')
    <!-- Support google header start -->
    <div itemscope itemtype="https://schema.org/Order">
        <div itemprop="merchant" itemscope itemtype="https://schema.org/Organization">
            <meta itemprop="name" content="Fluffici z.s"/>
        </div>
        <meta itemprop="orderNumber" content="{{ $publicData->public_identifier }}"/>
        <meta itemprop="priceCurrency" content="CZK"/>
        <meta itemprop="price" content="{{ $product->getNormalizedPrice() }}"/>
        <div itemprop="acceptedOffer" itemscope itemtype="https://schema.org/Offer">
            <div itemprop="itemOffered" itemscope itemtype="https://schema.org/Product">
                <meta itemprop="name" content="{{ $product->name }}"/>
                <meta itemprop="sku" content="{{ $product->id }}"/>
                <link itemprop="url" href="https://shop.fluffici.eu/checkout/{{ $product->id }}"/>
                <link itemprop="image" href="{{ $product->getImage() }}"/>
            </div>
            <meta itemprop="price" content="{{ $product->getNormalizedPrice() }}"/>
            <meta itemprop="priceCurrency" content="CZK"/>
            <div itemprop="eligibleQuantity" itemscope itemtype="https://schema.org/QuantitativeValue">
                <meta itemprop="value" content="1"/>
            </div>
            <div itemprop="seller" itemscope itemtype="https://schema.org/Organization">
                <meta itemprop="name" content="Fluffici z.s"/>
            </div>
        </div>
        <link itemprop="orderStatus" href="https://schema.org/OrderProcessing"/>
        <meta itemprop="orderDate" content="2027-11-07T23:30:00-08:00"/>
        <meta itemprop="isGift" content="false"/>
        <meta itemprop="discount" content="{{ $product->getProductSale() }}"/>
        <meta itemprop="discountCurrency" content="CZK"/>
        <div itemprop="customer" itemscope itemtype="https://schema.org/Person">
            <meta itemprop="name" content="{{ $order->first_name }} {{ $order->last_name }}"/>
        </div>
    </div>
    <!-- Support google header end -->
@endsection

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
                                        <img src="{{ url('/icons/checks.png') }}" class=" va-middle" width="40" height="40" alt="check" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: middle; font-size: 0; width: 40px; height: 40px; border-style: none; border-width: 0;" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md" style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 16px 0 0;" align="center">Order confirmation</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-xl" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 48px 48px 40px;">
                            <p style="margin: 0 0 1em;">Dear <strong style="font-weight: 600;">{{ $order->first_name }} {{ $order->last_name }}</strong>,</p>
                            <p style="margin: 0 0 1em;">
                                Thank you for your purchase on <strong style="font-weight: 600;">Fluffici z.s</strong>. It is our pleasure to confirm the following order.
                            </p>
                            <p class="mb-0" style="margin: 0;">
                                Your order PIN is:
                                <img src="{{ url('/icons/lock.png') }}" class=" va-middle" width="12" height="12" alt="lock" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: middle; font-size: 0; border-style: none; border-width: 0;" /> <strong style="font-weight: 600;">{{ $publicData->access_pin }}</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                        <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
                            <h4 style="font-weight: 600; color: #232b42; font-size: 16px; margin: 0 0 0.5em;">Order details</h4>
                            <table class="table" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                <tr>
                                    <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">Number</td>
                                    <td class="font-strong text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; padding: 4px 0 4px 12px;" align="right">#{{ $publicData->public_identifier }}</td>
                                </tr>
                                <tr>
                                    <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">Name</td>
                                    <td class="font-strong text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; padding: 4px 0 4px 12px;" align="right">{{ $order->first_name }} {{ $order->last_name }}</td>
                                </tr>
                                <tr>
                                    <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">Email</td>
                                    <td class="font-strong text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; padding: 4px 0 4px 12px;" align="right">{{ $order->email }}</td>
                                </tr>

                                @if(!empty($productTax))
                                    @foreach($productTax as $tax)
                                        <tr>
                                            <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">{{ $tax->name }}</td>
                                            <td class="font-strong text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; padding: 4px 0 4px 12px;" align="right">+{{ $tax->percentage }}%</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">VAT</td>
                                        <td class="font-strong text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; padding: 4px 0 4px 12px;" align="right">0%</td>
                                    </tr>
                                @endif

                                @if($product->getProductSale() != 0)
                                    <tr>
                                        <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">Discount</td>
                                        <td class="font-strong text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; padding: 4px 0 4px 12px;" align="right">-{{ $product->getProductSale() }}%</td>
                                    </tr>
                                @endif

                                @if($carrierFees <= 0)
                                    <tr>
                                        <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">Delivery fees</td>
                                        <td class="font-strong text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; padding: 4px 0 4px 12px;" align="right">No delivery fees.</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 4px 12px 4px 0;">Delivery fees</td>
                                        <td class="font-strong text-right" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; padding: 4px 0 4px 12px;" align="right">{{ $carrierFees }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td class="border-top" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-top-width: 1px; border-top-color: #dce0e5; border-top-style: solid; padding: 4px 12px 4px 0;">To pay</td>
                                    <td class="font-strong text-right border-top" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-weight: 600; border-top-width: 1px; border-top-color: #dce0e5; border-top-style: solid; padding: 4px 0 4px 12px;" align="right">{{ $product->getNormalizedPrice() + $carrierFees }} Kc</td>
                                </tr>
                            </table>
                            <div class="rounded p-md mt-lg border border-green" style="border-radius: 4px; margin-top: 24px; padding: 16px; border: 1px solid #dce0e5;">
                                <table class="row row-flex" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; table-layout: auto;">
                                    <tr>
                                        @if($order->status === "OUTING")
                                            <td class="col w-1p" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; width: 1%;" valign="top">
                                                <img src="https://dashboard.fluffici.eu/api/generate/order/{{ $order->order_id }}" width="108" height="108" alt="" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: baseline; font-size: 0; border-style: none; border-width: 0;" />
                                            </td>
                                            <td class="col-spacer" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; width: 24px;" valign="top"></td>
                                            <td class="col" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" valign="top">
                                                <h4 class="text-green text-uppercase" style="font-weight: 600; color: #2fb344; font-size: 16px; text-transform: uppercase; margin: 0 0 0.5em;">The order has to be paid</h4>
                                                <div>
                                                    Amount: {{ $product->getNormalizedPrice() }} Kc
                                                </div>
                                                <div class="text-muted mt-md" style="color: #667382; margin-top: 16px;">Show this confirmation at the Fluffici z.s staff.</div>
                                            </td>
                                        @else
                                            <td class="col" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" valign="top">
                                                <h4 class="text-green text-uppercase" style="font-weight: 600; color: #2fb344; font-size: 16px; text-transform: uppercase; margin: 0 0 0.5em;">The order was paid</h4>
                                                <div>
                                                    Amount: {{ $product->getNormalizedPrice() }} Kc
                                                </div>
                                                <div class="text-muted mt-md" style="color: #667382; margin-top: 16px;">Show this confirmation at the Fluffici z.s staff.</div>
                                            </td>
                                        @endif
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="content border-top" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-top-width: 1px; border-top-color: #dce0e5; border-top-style: solid; padding: 40px 48px;">
                            <h4 style="font-weight: 600; color: #232b42; font-size: 16px; margin: 0 0 0.5em;">Payment</h4>
                            <ul style="margin: 0 0 1em;">
                                <li>Payment types accepted at outings: Cash, Voucher</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 0 48px 40px;">
                            <table class="row" cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td class="col" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;" valign="top">
                                        <table cellspacing="0" cellpadding="0" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: collapse; width: 100%;">
                                            <tr>
                                                <td align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: separate; width: 100%; color: #ffffff; border-radius: 4px;" bgcolor="#206bc4">
                                                        <tr>
                                                            <td align="center" valign="top" class="lh-1" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; line-height: 100%;">
                                                                <a href="https://shop.fluffici.eu/order/confirmation/{{ $publicData->internal }}" class="btn bg-blue border-blue" style="color: #ffffff; text-decoration: none; white-space: nowrap; font-weight: 500; font-size: 16px; border-radius: 4px; line-height: 100%; display: block; -webkit-transition: 0.3s background-color; -o-transition: 0.3s background-color; transition: 0.3s background-color; background-color: #206bc4; padding: 12px 32px; border: 1px solid #206bc4;">
                                                                    <span class="btn-span" style="color: #ffffff; font-size: 16px; text-decoration: none; white-space: nowrap; font-weight: 500; line-height: 100%;">Print confirmation</span>
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
                                                    <table cellpadding="0" cellspacing="0" border="0" class="bg-secondary rounded" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; border-collapse: separate; width: 100%; color: #ffffff; border-radius: 4px;" bgcolor="#f0f1f3">
                                                        <tr>
                                                            <td align="center" valign="top" class="lh-1" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; line-height: 100%;">
                                                                <a href="https://shop.fluffici.eu/order/cancel/{{ $publicData->internal }}" class="btn bg-secondary border-secondary" style="color: #667382; text-decoration: none; white-space: nowrap; font-weight: 500; font-size: 16px; border-radius: 4px; line-height: 100%; display: block; -webkit-transition: 0.3s background-color; -o-transition: 0.3s background-color; transition: 0.3s background-color; background-color: #f0f1f3; padding: 12px 32px; border: 1px solid #f0f1f3;">
                                                                    <span class="btn-span" style="color: #667382; font-size: 16px; text-decoration: none; white-space: nowrap; font-weight: 500; line-height: 100%;">Cancel order</span>
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
