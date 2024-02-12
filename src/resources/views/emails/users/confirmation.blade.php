@extends('emails.base')

@section('head')
    <!-- Support google header start -->
    <div itemscope itemtype="http://schema.org/Order">
        <div itemprop="merchant" itemscope itemtype="http://schema.org/Organization">
            <meta itemprop="name" content="Fluffici z.s"/>
        </div>
        <meta itemprop="orderNumber" content="{{ substr($order_id, 0, 16) }}"/>
        <meta itemprop="priceCurrency" content="CZK"/>
        <meta itemprop="price" content="{{ $price }}"/>
        <div itemprop="acceptedOffer" itemscope itemtype="http://schema.org/Offer">
            <div itemprop="itemOffered" itemscope itemtype="http://schema.org/Product">
                <meta itemprop="name" content="{{ $product->name }}"/>
                <meta itemprop="sku" content="{{ $product->id }}"/>
                <link itemprop="url" href="https://shop.fluffici.eu/checkout/{{ $product->id }}"/>
                <link itemprop="image" href="{{ $product->getImage() }}"/>
            </div>
            <meta itemprop="price" content="{{ $price }}"/>
            <meta itemprop="priceCurrency" content="CZK"/>
            <div itemprop="eligibleQuantity" itemscope itemtype="http://schema.org/QuantitativeValue">
                <meta itemprop="value" content="1"/>
            </div>
            <div itemprop="seller" itemscope itemtype="http://schema.org/Organization">
                <meta itemprop="name" content="Fluffici z.s"/>
            </div>
        </div>
        <link itemprop="orderStatus" href="http://schema.org/OrderProcessing"/>
        <meta itemprop="orderDate" content="2027-11-07T23:30:00-08:00"/>
        <meta itemprop="isGift" content="false"/>
        <meta itemprop="discount" content="{{ $discount }}"/>
        <meta itemprop="discountCurrency" content="CZK"/>
        <div itemprop="customer" itemscope itemtype="http://schema.org/Person">
            <meta itemprop="name" content="{{ $first_name }} {{ $last_name }}"/>
        </div>
    </div>
    <!-- Support google header end -->
@endsection

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
                                        <img src="{{ url('/icons/checks.png') }}" class=" va-middle" width="40" height="40" alt="check" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">Order confirmation</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-xl">
                            <p>Dear {{ $first_name }} {{ $last_name }},</p>
                            <p>
                                Thank you for your purchase on <strong>Fluffici z.s</strong>. It is our pleasure to confirm the following order.
                            </p>
                        </td>
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                        <td class="content">
                            <h4>Order details</h4>
                            <table class="table" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>Number</td>
                                    <td class="font-strong text-right">#{{ substr($order_id, 0, 16) }}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td class="font-strong text-right">{{ $first_name }} {{ $last_name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td class="font-strong text-right">{{ $email }}</td>
                                </tr>
                                <tr>
                                    <td class="border-top">To pay</td>
                                    <td class="font-strong text-right border-top">{{ $price }} Kc</td>
                                </tr>
                                <tr>
                                    <td>Delivery fees</td>
                                    <td class="font-strong text-right">0 Kc</td>
                                </tr>
                            </table>
                            <div class="rounded p-md mt-lg border border-green">
                                <table class="row row-flex" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="col w-1p">
                                            <img src="https://dashboard.fluffici.eu/api/generate/order/{{ $order_id }}" width="108" height="108" alt="" />
                                        </td>
                                        <td class="col-spacer"></td>
                                        <td class="col">
                                            <h4 class="text-green text-uppercase">The order need to be paid</h4>
                                            <div>
                                                Amount: {{ $price }} Kc
                                            </div>
                                            <div class="text-muted mt-md">Show this confirmation at the Fluffici z.s staff.</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="content border-top">
                            <h4>Payment</h4>
                            <ul>
                                <li>Payment types accepted at outings: Cash, Voucher</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0">
                            <table class="row" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="col">
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded">
                                                        <tr>
                                                            <td align="center" valign="top" class="lh-1">
                                                                <a href="https://shop.fluffici.eu/order/confirmation/{{ $order_id }}" class="btn bg-blue border-blue">
                                                                    <span class="btn-span">Print&nbsp;confirmation</span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="col-spacer"></td>
                                    <td class="col">
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="bg-secondary rounded">
                                                        <tr>
                                                            <td align="center" valign="top" class="lh-1">
                                                                <a href="https://shop.fluffici.eu/order/cancel/{{ $order_id }}" class="btn bg-secondary border-secondary">
                                                                    <span class="btn-span">Cancel&nbsp;order</span>
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
