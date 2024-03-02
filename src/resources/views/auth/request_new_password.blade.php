@extends('auth')
@section('title', 'Obnova hesla')

@section('content')
    <h1 class="h4 text-white mb-4">Obnovte své heslo</h1>

    <form class="m-t-md"
          role="form"
          method="POST"
          data-controller="form"
          data-form-need-prevents-form-abandonment-value="false"
          data-action="form#submit"
          action="{{ route('login.recovery') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label text-white">
                Prosím, zadejte svůj email
            </label>

            @if(isset($email))
                {!!  \Orchid\Screen\Fields\Input::make('email')
                      ->required()
                      ->tabindex(1)
                      ->autofocus()
                      ->value($email)
                      ->placeholder('Zadejte svůj email.')
               !!}
            @else
                {!!  \Orchid\Screen\Fields\Input::make('email')
                       ->required()
                       ->tabindex(1)
                       ->autofocus()
                       ->placeholder('Zadejte svůj email.')
                !!}
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label w-100 text-white">
                Captcha
            </label>

            <x-turnstile-widget
                theme="light"
                language="pl"
                size="normal"
            />
        </div>

        <div class="row align-items-center">
            <div class="col-md-6 col-xs-12">
                <button id="button-login" type="submit" class="btn btn-primary btn-block" tabindex="3">
                    <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
                    Pokračovat
                </button>
            </div>
        </div>
    </form>
@endsection
