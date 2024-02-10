@extends('emails.base')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content pb-0" align="center">
                            <table class="icon icon-lg bg-green" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td valign="middle" align="center">
                                        <img src="{{ url('icons/truck.png') }}" class=" va-middle" width="40" height="40" alt="truck" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">Your order has been shipped!</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <p>Hi {{ $order->first_name }}, <br>
                                We are very glad to inform you that your order has been already shipped!</p>
                            <p>You can track your shipment on our website: <a href="https://shop.fluffici.eu/order/track/{{ $order->order_id }}">Fluffici</a></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded w-auto">
                                            <tr>
                                                <td align="center" valign="top" class="lh-1">
                                                    <a href="https://shop.fluffici.eu/order/track/{{ $order->order_id }}" class="btn bg-blue border-blue">
                                                        <span class="btn-span">Track&nbsp;your&nbsp;order</span>
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
