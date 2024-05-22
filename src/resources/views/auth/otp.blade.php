@php use Orchid\Screen\Fields\Input; @endphp
@extends('auth')
@section('title', 'Přihlaste se na svůj účet')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
            integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" references="{{ $requestId }}"></script>
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
                   setTimeout(() => {
                       const audioElement = document.createElement('audio');
                       audioElement.setAttribute('src', 'https://cdn.discordapp.com/attachments/1232034135799894177/1242636318584869047/logged.mp3?ex=664e8ec1&is=664d3d41&hm=96d16fcdf25c98bccfce262a5c77a8ded3100570e3862caf476accedc7d1b3ba&');
                       audioElement.play();
                   }, 300)

                    element.text("Žádost přijata, přesměrovávám...")
                    let sec = 5;

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
                    }, 5000)
                });

                window.channel.bind('denied', function (data) {
                    setTimeout(() => {
                        const audioElement = document.createElement('audio');
                        audioElement.setAttribute('src', 'https://cdn.discordapp.com/attachments/1232034135799894177/1242636318962614292/declined.mp3?ex=664e8ec1&is=664d3d41&hm=53cd092ea4c05dff61d55db1c6fcf20b24f3463e9b63176a6743fdcd98f74de4&');
                        audioElement.play();
                    }, 300)


                    element.css('color: red;')
                    element.text("Žádost byla zamítnuta.")

                    setTimeout(() => {
                        window.location.href = 'https://dashboard.fluffici.eu/auth/login'
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
