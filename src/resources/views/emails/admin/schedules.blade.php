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
                                        <img src="{{ url('/icons/augmented-reality.png') }}" class=" va-middle" width="40" height="40" alt="lock-open" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">{{ $title }}'s schedule</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0">
                            <table class="list list-md" cellspacing="0" cellpadding="0">
                                @forelse($schedules as $schedule)
                                    <tr class="list-item">
                                        <td class="w-1p">
                                            {{ $schedule->beging }}<br />
                                            <span class="text-muted">{{ $schedule->end->diffForHumans() }}</span>
                                        </td>
                                        <td class="w-1p pl-md pr-md">
                                            <img src="{{ url('/icons/checks.png') }}" class=" avatar d-block " width="40" height="40" alt="" />
                                        </td>
                                        <td>
                                            {{ $schedule->name }}<br>
                                            <span class="text-muted">Interested : {{ $schedule->interested }}</span>
                                        </td>
                                        @if($schedule->status == "STARTED")
                                            <td class="text-center d-mobile-none">
                                                <img src="{{ url('/icons/augmented-reality.png') }}" class=" va-middle" width="24" height="24" alt="star" /><br>
                                                <span class="text-green text-uppercase">Started</span>
                                            </td>
                                        @else
                                            <td class="text-center d-mobile-none">
                                                <img src="{{ url('/icons/clock.png') }}" class=" va-middle" width="24" height="24" alt="star" /><br>
                                                <span class="text-blue text-uppercase">On time</span>
                                            </td>
                                        @endif
                                    </tr>

                                    @empty
                                        <tr class="list-item">
                                            <td class="w-1p pl-md pr-md">
                                                <img src="{{ url('/icons/circle-minus.png') }}" class=" avatar d-block " width="40" height="40" alt="" />
                                            </td>
                                            <td>
                                                No {{ $title }} scheduled.
                                            </td>
                                        </tr>

                                @endforelse
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
