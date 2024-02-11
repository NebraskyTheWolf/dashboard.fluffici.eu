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
                                        <img src="{{ url('icons/alert-triangle.png') }}" class=" va-middle" width="40" height="40" alt="alert-triangle" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">Application error</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <h4>New alert from https://dashboard.fluffici.eu</h4>
                            <p class="text-muted">{{ $currentDate }}</p>
                            <h4 class="mt-lg">{{ $class }}</h4>
                            <pre>{{ $message }}</pre>
                            <h4 class="mt-lg">Stack trace</h4>
                            <table class="table-pre" cellspacing="0" cellpadding="0">
                                <tr class="table-pre-line-highlight table-pre-line-highlight-red">
                                    <td class="table-pre-line">{{ $line }}</td>
                                    <td>
                                        <pre class="highlight">  {{ $code }}</pre>
                                    </td>
                                </tr>
                            </table>
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
                                                    <a href="https://dashboard.fluffici.eu/developer/debug" class="btn bg-blue border-blue">
                                                        <span class="btn-span">Debug</span>
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
