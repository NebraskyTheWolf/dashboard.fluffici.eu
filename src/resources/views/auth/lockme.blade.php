<div class="mb-3 d-flex align-items-center">
    <span class="thumb-sm avatar me-3">
        <img src="{{ $lockUser->presenter()->image() }}" class="b bg-light" alt="test">
    </span>
    <span style="width:125px;" class="small">
        <span class="text-ellipsis">{{ $lockUser->presenter()->title() }}</span>
        <span class="text-muted d-block text-ellipsis">{{ $lockUser->presenter()->subTitle() }}</span>
    </span>
    <input type="hidden" name="email" required value="{{ $lockUser->email }}">
</div>

@error('email')
    <span class="d-block invalid-feedback text-danger">
            {{ $errors->first('email') }}
    </span>
@enderror

<div class="mb-3">
    <input type="hidden" name="remember" value="true">

    {!!  \Orchid\Screen\Fields\Password::make('password')
            ->required()
            ->autocomplete('current-password')
            ->tabindex(1)
            ->autofocus()
            ->placeholder(__('Zadejte své heslo'))
    !!}
</div>

<div class="row align-items-center">
    <div class="col-md-6 col-xs-12">
        <a href="{{ route('login.lock') }}" class="small">
            Přihlásit se jako jiný uživatel.
        </a>
    </div>
    <div class="col-md-6 col-xs-12">
        <button id="button-login " type="submit" class="btn btn-primary btn-block " tabindex="2">
            <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
            Přihlásit se
        </button>
    </div>
</div>
