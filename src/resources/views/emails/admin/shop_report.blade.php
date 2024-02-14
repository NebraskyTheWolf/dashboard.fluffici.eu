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
                                        <img src="{{ url('/icons/download.png') }}" class=" va-middle" width="40" height="40" alt="download" />
                                    </td>
                                </tr>
                            </table>
                            <h1 class="text-center m-0 mt-md">All the monthly report is ready.</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            Use the link below to preview the reports. Please ensure that the entire URL is copied to your web browser.
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0">
                            <table class="list" cellspacing="0" cellpadding="0">

                                @foreach($files as $file)
                                    <tr class="list-item">
                                        <td class="lh-1">
                                            <img src="{{ url('/icons/file.png') }}" class=" va-bottom mr-sm va-middle" width="16" height="16" alt="file" />{{ $file['name'] }}
                                        </td>
                                        <td class="text-right text-muted">{{ $file['size'] }}</td>
                                    </tr>
                                @endforeach

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
                                                    <a href="https://tabler.io/emails?utm_source=demo" class="btn bg-blue border-blue">
                                                        <span class="btn-span">Go to the dashboard</span>
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
