@extends('emails.base-gmail')

@section('content')
    <table class="box" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content text-center">
                            <div>
                                <img src="{{ url('/illustrations/undraw_gdpr_3xfb.png') }}" alt="" height="160" class="img-illustration" />
                            </div>
                            <h1 class="mb-0 mt-lg">We're updating our GDPR terms</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0">
                            <p>We are contacting you as the billing contact or technical contact for fluffici product.</p>
                            <p>We want to let you know that we are retiring the fluffici Customer Agreement and replacing it with new
                                legal
                                terms that are specific to deployment type.</p>
                            <p>Here are some of the key changes:</p>
                            <ul>
                                {!! $content !!}
                            </ul>
                            <p>Thank you for being Fluffici customer!</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content pt-0">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="va-top pr-md w-1p">
                                        <img src="{{ $author->getImage() }}" class=" avatar " width="48" height="48" alt="" />
                                    </td>
                                    <td class="va-middle">
                                        {{ $author->getName() }}<br />
                                        <span class="text-muted">{{ $author->getRole() }}</span>
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
