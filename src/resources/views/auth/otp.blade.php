@php use Orchid\Screen\Fields\Input; @endphp
@extends('auth')
@section('title', 'Přihlaste se na svůj účet')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js" cache-reference="{{ $requestId }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
            integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" cache-reference="{{ $requestId }}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        window.pusher = new Pusher('b71408463b934d2e96e1', {
            cluster: 'eu'
        });

        @if($isRequest)
            $(document).ready(function () {
                window.channel = window.pusher.subscribe('{{ $requestId }}')
                const element = $("#status_{{ $requestId }}");

                // Subscribing to the requestId of the OTP-Request.
                window.channel.bind('accepted', function (data) {
                    var audioElement = document.createElement('audio');
                    audioElement.setAttribute('src', 'https://autumn.fluffici.eu/attachments/DtSGOGPJV4LppXs39ufpJGTlnvsWtQ3VaclUXhy89V');
                    audioElement.play();

                    element.text("Žádost přijata, přesměrovávám...")
                    let sec = 10;

                    const countdown = setInterval(() => {
                        sec--
                        if(sec > 0) {
                            element.text(`Přesměrování za ${sec} sekund.`)
                        } else {
                            clearInterval(countdown)
                        }
                    }, 1000)

                    setTimeout(() => {
                        window.location.href = 'https://dashboard.fluffici.eu/auth/magic?requestId={{ $requestId }}'
                    }, 10000)
                });

                window.channel.bind('denied', function (data) {
                    var audioElement = document.createElement('audio');
                    audioElement.setAttribute('src', 'https://autumn.fluffici.eu/attachments/QSUloX_uJXSZbgxZf0Ykzdgv1_UG3VJ3CE02giBWcj');
                    audioElement.play();

                    element.css('color', 'red')
                    element.text("Žádost byla zamítnuta.")

                    setTimeout(() => {
                        window.location.href = 'https://dashboard.fluffici.eu/auth'
                    }, 5000)
                });
            })
        @endif
    </script>
@endsection

@section('content')
    @if($isRequest)
        <h1 class="h4 text-white mb-4">Prosím, podívejte se do RESYS a potvrďte žádost o přihlášení.</h1>
    @else
        <h1 class="h4 text-white mb-4">Zkontrolujte prosím svůj email.</h1>
    @endif


    <form class="m-t-md"
          role="form"
          method="POST"
          data-controller="form"
          data-form-need-prevents-form-abandonment-value="false"
          data-action="form#submit"
          action="{{ route('login.otp') }}">
        @csrf

        @if($isRequest)
            <div class="mb-3">
                <label id="status_{{ $requestId }}" class="form-label text-white">
                    Čekám na potvrzení...
                </label>
            </div>
        @else
            <div class="mb-3">
                <label class="form-label text-white">
                    OTP Kód
                </label>

                {!!  Input::make('otp')
                    ->type('number')
                    ->required()
                    ->tabindex(1)
                    ->autofocus()
                    ->inputmode('otp')
                    ->placeholder('Zadejte svůj OTP token')
                    ->help('Na váš email byl zaslán autentizační kód.')
                !!}
            </div>

            <div class="row align-items-center">
                <div class="col-md-6 col-xs-12">
                    <a class="small"> Kód vyprší za:
                        <div class="small" id="otp-expiration">30:00</div>
                    </a>
                </div>
                <div class="col-md-6 col-xs-12">
                    <button id="button-login" type="submit" class="btn btn-primary btn-block" tabindex="3">
                        <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
                        Pokračovat
                    </button>
                </div>
            </div>
        @endif
    </form>
@endsection
