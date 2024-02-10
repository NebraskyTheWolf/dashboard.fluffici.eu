@extends('emails.base')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content pb-0" align="center">
                            <table class="icon icon-lg bg-blue" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td valign="middle" align="center">
                                        <img src="{{ url('icons/gift.png') }}" class=" va-middle" width="40" height="40" alt="gift" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center">
                            <h1><strong>{{ $percentage }}%</strong> Off on {{ $productName }}</h1>
                            <p class="text-muted mb-0">{{ $message }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <table cellspacing="0" cellpadding="0" class="w-auto" align="center">
                                <tr>
                                    <td class="border-dashed border-wide border-dark text-center rounded px-lg py-md">
                                        <div class="h1 font-strong m-0">{{ $promoCode }}</div>
                                        <div class="text-muted">Expires on {{ $expiry->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" class="bg-green rounded w-auto">
                                            <tr>
                                                <td align="center" valign="top" class="lh-1">
                                                    <a href="https://shop.fluffici.eu" class="btn bg-green border-green">
                                                        <span class="btn-span">Go&nbsp;to&nbsp;shop&nbsp;now</span>
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
