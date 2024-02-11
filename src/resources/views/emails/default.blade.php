@extends('emails.base')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content">
                            {!! $content !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
