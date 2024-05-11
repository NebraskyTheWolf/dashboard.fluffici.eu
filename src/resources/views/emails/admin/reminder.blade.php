@extends('emails.base')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content pb-0" align="center">
                            <h1 class="text-center m-0">Plánovaná událost dnes!</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <div class="rounded bg-light p-md mb-lg">
                                <table class="row row-flex" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="col text-mobile-center w-1p">
                                            <table class="day" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td class="day-month">{{ $month }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="day-number">{{ $day }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="day-weekday">{{ $time }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="col-spacer col-spacer-sm"></td>
                                        <td class="col">
                                            <h3 class="m-0 font-strong">{{ $eventName }}</h3>
                                            <div class="text-muted mb-sm">{{ $dayFull }}, {{ $monthFull }} {{ $day }} v {{ $time }}</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <h4 class="mt-xl">Detaily</h4>
                            <p>Je zde {{ $interested }} lidí, kteří mají zájem o tuto událost.</p>
                            <small>Přejeme vám hezký den během této eventu {{ $name }}.</small>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
