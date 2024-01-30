<div class="mb-3">

    <label class="form-label text-white">
        Email address
    </label>

    {!!  \Orchid\Screen\Fields\Input::make('email')
        ->type('email')
        ->required()
        ->tabindex(1)
        ->autofocus()
        ->autocomplete('email')
        ->inputmode('email')
        ->placeholder('Enter your email')
    !!}
</div>

<div class="mb-3">
    <label class="form-label w-100 text-white">
        Password
    </label>

    {!!  \Orchid\Screen\Fields\Password::make('password')
        ->required()
        ->autocomplete('current-password')
        ->tabindex(2)
        ->placeholder('Enter your password')
    !!}
</div>

<div class="row align-items-center">
    <div class="col-md-6 col-xs-12">
        <label class="form-check">
            <input type="hidden" name="remember">
            <input type="checkbox" name="remember" value="true"
                   class="form-check-input" {{ !old('remember') || old('remember') === 'true'  ? 'checked' : '' }}>
            <span class="form-check-label text-white"> Remember Me</span>
        </label>
    </div>
    <div class="col-md-6 col-xs-12">
        <button id="button-login" type="submit" class="btn btn-primary btn-block" tabindex="3">
            <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
            Login
        </button>
    </div>
</div>
