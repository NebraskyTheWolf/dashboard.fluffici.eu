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
                                        <img src="{{ url('/icons/checks.png') }}" class=" va-middle" width="40" height="40" alt="check" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">Order confirmation</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-xl">
                            <p>Dear {{ $order->first_name }} {{ $order->last_name }},</p>
                            <p>
                                Thank you for your purchase on <strong>Fluffici z.s</strong>. It is our pleasure to confirm the following order.
                            </p>
                            <p class="mb-0">
                                Your order PIN is:
                                <img src="{{ url('/icons/lock.png') }}" class=" va-middle" width="12" height="12" alt="lock" /> <strong>{{ $orderPin }}</strong>
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
                                    <td class="font-strong text-right">#{{ substr($order->order_id, 0, 16) }}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td class="font-strong text-right">{{ $order->first_name }} {{ $order->last_name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td class="font-strong text-right">{{ $order->email }}</td>
                                </tr>
                                <tr>
                                    <td class="border-top">To pay</td>
                                    <td class="font-strong text-right border-top">{{ $orderPayment->price }} Kc</td>
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
                                            <img src="https://dashboard.fluffici.eu/order/datamatrix/{{ $order->order_id }}" width="108" height="108" alt="" />
                                        </td>
                                        <td class="col-spacer"></td>
                                        <td class="col">
                                            <h4 class="text-green text-uppercase">The order need to be paid</h4>
                                            <div>
                                                Amount: {{ $orderPayment->price }} Kc
                                            </div>
                                            <div class="text-muted mt-md">Show this confirmation at the Fluffici staff.</div>
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
                            <h4>Return Policy</h4>
                            <ul>
                                {!! $policies !!}
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
                                                                <a href="https://shop.fluffici.eu/order/confirmation/{{ $order->order_id }}" class="btn bg-blue border-blue">
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
                                                                <a href="https://shop.fluffici.eu/order/cancel/{{ $order->order_id }}" class="btn bg-secondary border-secondary">
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
