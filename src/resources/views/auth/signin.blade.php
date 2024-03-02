<div class="mb-3">

    <label class="form-label text-white">
        E-mailová adresa
    </label>

    {!!  \Orchid\Screen\Fields\Input::make('email')
        ->type('email')
        ->required()
        ->tabindex(1)
        ->autofocus()
        ->autocomplete('email')
        ->inputmode('email')
        ->placeholder('Zadejte svůj e-mail')
    !!}
</div>

<div class="mb-3">
    <label class="form-label w-100 text-white">
        Heslo
    </label>

    {!!  \Orchid\Screen\Fields\Password::make('password')
        ->required()
        ->autocomplete('current-password')
        ->tabindex(2)
        ->placeholder('Zadejte své heslo')
    !!}
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
        <label class="form-check">
            <input type="hidden" name="remember">
            <input type="checkbox" name="remember" value="true"
                   class="form-check-input" {{ !old('remember') || old('remember') === 'true'  ? 'checked' : '' }}>
            <span class="form-check-label text-white"> Zapamatuj si mě</span>
        </label>
    </div>
    <div class="col-md-6 col-xs-12">
        <button id="button-login" type="submit" class="btn btn-primary btn-block" tabindex="3">
            <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
            Přihlásit se
        </button>
    </div>
</div>
