@extends('auth')
@section('title', 'Obnova hesla')

@section('content')
    <h1 class="h4 text-white mb-4">Vytvořte nové heslo</h1>

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
                Nové heslo
            </label>

            {!!  \Orchid\Screen\Fields\Password::make('new_password')
                    ->required()
                    ->tabindex(1)
                    ->autofocus()
                    ->placeholder('Zadejte nové heslo')
            !!}

            <input name="token" id="token" value="{{ $token }}" hidden="" required>
        </div>

        <div class="mb-3">
            <label class="form-label w-100 text-white">
                Captcha
            </label>

            <x-turnstile-widget
                theme="auto"
                language="auto"
                size="normal"
            />
        </div>
        <div class="row align-items-center">
            <div class="col-md-6 col-xs-12">
                <button id="button-login" type="submit" class="btn btn-primary btn-block" tabindex="3">
                    <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
                    Změnit heslo
                </button>
            </div>
        </div>
    </form>
@endsection
