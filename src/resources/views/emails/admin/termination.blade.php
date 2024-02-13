@extends('emails.' . $provider)

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
                                        <img src="{{ url('/icons/alert-octagon.png') }}" class=" va-middle" width="40" height="40" alt="lock-open" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">Account termination</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content text-center">
                            <p>Your account got terminated because you was not respecting our <strong>Policies</strong>, Please refer to your superior admin.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
