<div class="profile-container d-flex align-items-stretch p-3 rounded lh-sm position-relative overflow-hidden">

    <a href="{{ route(config('platform.profile', 'platform.profile')) }}" class="col-10 d-flex align-items-center me-3">
        @if($image = Auth::user()->presenter()->image())
            <img id="persona-avatar" src="{{$image}}"  alt="{{ Auth::user()->presenter()->title()}}" class="thumb-sm avatar b me-3" type="image/*">
        @endif

        <small class="d-flex flex-column" style="line-height: 16px;">
            <span class="text-ellipsis text-white" id="persona-title">{{Auth::user()->presenter()->title()}}</span>
            <span class="text-ellipsis text-muted" id="persona-subtitle">{{Auth::user()->presenter()->subTitle()}}</span>
        </small>
    </a>

    <x-orchid-notification/>

</div>
