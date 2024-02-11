@extends('auth')
@section('title', 'Sign in to your account')

@section('content')
    <h1 class="h4 text-white mb-4">Please check your email.</h1>

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
                OTP Code
            </label>

            {!!  \Orchid\Screen\Fields\Input::make('otp')
                ->type('number')
                ->required()
                ->tabindex(1)
                ->autofocus()
                ->inputmode('otp')
                ->placeholder('Enter your otp token')
                ->help('You received a email with your authentication code.')
            !!}
        </div>

        <div class="row align-items-center">
            <div class="col-md-6 col-xs-12">
                <a class="small"> The code will expire in : </a>
                <div class="small" id="otp-expiration">30:00</div>
            </div>
            <div class="col-md-6 col-xs-12">
                <button id="button-login" type="submit" class="btn btn-primary btn-block" tabindex="3">
                    <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
                    Continue
                </button>
            </div>
        </div>
    </form>
@endsection
