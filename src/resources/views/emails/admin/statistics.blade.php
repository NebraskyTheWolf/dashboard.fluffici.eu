@extends("emails.base")

@section('content')
    <table class="box" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content border-bottom">
                            <table class="row row-flex" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="col text-mobile-center text-right pl-md">
                                        <span class="font-strong font-lg">Weekly Update for Fluffici</span><br />
                                        <span class="text-muted">{{ $range }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <h4 class="mb-xl">Statistics</h4>
                            <table class="row" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="col text-center va-top">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <table class="w-auto" width="80" cellspacing="0" cellpadding="0" align="center">
                                                        <tr>
                                                            <td width="80" height="80" style="background-image: url({{ url('chart-donuts/blue/' . $percentage . '.png') }}); background-size: 100%;" valign="center" class="text-default text-center">
                                                                <div class="h4 m-0 text-blue lh-1">
                                                                    {{ $vists }}
                                                                    <div class="text-muted font-normal font-sm mt-xs">of {{ $vistsPrevious }} last week</div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pt-sm text-center">
                                                    Visits over a week
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="col-hr"></td>
                                    <td class="col text-center va-top">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <table class="w-auto" width="80" cellspacing="0" cellpadding="0" align="center">
                                                        <tr>
                                                            <td width="80" height="80" style="background-image: url({{ url('chart-donuts/green/' . $percentageOrder . '.png') }}); background-size: 100%;" valign="center" class="text-default text-center">
                                                                <div class="h4 m-0 text-green lh-1">
                                                                    {{ $orderCount }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pt-sm text-center">
                                                    Order completed
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="col-hr"></td>
                                    <td class="col text-center va-top">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <table class="w-auto" width="80" cellspacing="0" cellpadding="0" align="center">
                                                        <tr>
                                                            <td width="80" height="80" style="background-image: url({{ url('chart-donuts/red/' . $percentageOverdue . '.png') }}); background-size: 100%;" valign="center" class="text-default text-center">
                                                                <div class="h4 m-0 text-red lh-1">
                                                                    {{ $percentageOverdue }}
                                                                    <div class="text-muted font-normal font-sm mt-xs">of 400</div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pt-sm text-center">
                                                    Overdue percentage
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <h4 class="mt-xl">Newsletter stats</h4>
                            <table class="row" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="col text-center va-top">
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="text-left pb-xs">Delivered</td>
                                                <td class="text-right pb-xs text-muted">{{ $delivered }}%</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="chart" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td width="{{ $delivered }}%" class="chart-percentage font-sm bg-green" style="height: 8px"></td>
                                                            <td width="{{ $delivered - 100 }}%" class="chart-percentage font-sm bg-green-lightest" style="height: 8px"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="col-spacer"></td>
                                    <td class="col text-center va-top">
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="text-left pb-xs">On the way</td>
                                                <td class="text-right pb-xs text-muted">{{ $shipping }}%</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="chart" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td width="{{ $shipping }}%" class="chart-percentage font-sm bg-yellow" style="height: 8px"></td>
                                                            <td width="{{ $shipping - 100 }}%" class="chart-percentage font-sm bg-yellow-lightest" style="height: 8px"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="col-spacer"></td>
                                    <td class="col text-center va-top">
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="text-left pb-xs">Cancelled</td>
                                                <td class="text-right pb-xs text-muted">{{ $cancelled }}%</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="chart" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td width="{{ $cancelled }}%" class="chart-percentage font-sm bg-red" style="height: 8px"></td>
                                                            <td width="{{ $cancelled - 100 }}%" class="chart-percentage font-sm bg-red-lightest" style="height: 8px"></td>
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
                    <tr>
                        <td class="content">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded">
                                            <tr>
                                                <td align="center" valign="top" class="lh-1">
                                                    <a href="https://dashboard.fluffici.eu/shop/statistics" class="btn bg-blue border-blue">
                                                        <span class="btn-span">See&nbsp;my&nbsp;full&nbsp;report</span>
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
