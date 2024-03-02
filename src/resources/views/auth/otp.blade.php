@extends('auth')
@section('title', 'Přihlaste se na svůj účet')

@section('content')
    <h1 class="h4 text-white mb-4">Zkontrolujte prosím svůj email.</h1>

    <form class="m-t-md"
          role="form"
          method="POST"
          data-controller="form"
          data-form-need-prevents-form-abandonment-value="false"
          data-action="form#submit"
          action="{{ route('login.otp') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label text-white">
                OTP Kód
            </label>

            {!!  \Orchid\Screen\Fields\Input::make('otp')
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
                <a class="small"> Kód vyprší za: <div class="small" id="otp-expiration">30:00</div></a>
            </div>
            <div class="col-md-6 col-xs-12">
                <button id="button-login" type="submit" class="btn btn-primary btn-block" tabindex="3">
                    <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
                    Pokračovat
                </button>
            </div>
        </div>
    </form>
@endsection
