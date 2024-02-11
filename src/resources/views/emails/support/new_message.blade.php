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
                                        <img src="{{ url('/icons/message.png') }}" class=" va-middle" width="40" height="40" alt="message" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">New message</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <p>Hi {{ $order->first_name }},</p>
                            <p><a href="">{{ $ticket->assignee }}</a> responded to your message about your order on <strong>Fluffici</strong>:</p>
                            <table class="my-xl" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="w-1p va-top pr-md">
                                        <img src="{{ $avatarURL }}" class=" avatar " width="48" height="48" alt="" />
                                    </td>
                                    <td>
                                        <div class="quote">
                                            <div class="font-strong">{{ $ticker->assignee }} says:</div>
                                            {{ $ticket->message }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table class="row" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="col">
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded">
                                                        <tr>
                                                            <td align="center" valign="top" class="lh-1">
                                                                <a href="https://shop.fluffici.eu/support/ticket/{{ $ticket->id }}/reply" class="btn bg-blue border-blue">
                                                                    <span class="btn-span">Reply&nbsp;now</span>
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
                                                                <a href="https://shop.fluffici.eu/support/ticket/{{ $ticket->id }}/close" class="btn bg-secondary border-secondary">
                                                                    <span class="btn-span">Close my ticket.</span>
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
