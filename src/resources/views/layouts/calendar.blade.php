<div
    data-controller="calendar"
    data-calendar-parent="#{{$slug}}"
    data-calendar-editable="{{$editable}}"
    data-calendar-color="{{$color}}"
    data-calendar-locale="{{$locale}}"
    data-calendar-initialView="{{$initialView}}"
    data-calendar-google-calendar="{{$googleCalendar}}"
    data-calendar-google-calendar-secret="{{$googleCalendarSecret}}"
    data-calendar-google-calendar-url="{{$googleCalendarURL}}"
>
    <div class="bg-white rounded shadow-sm mb-3 pt-3">
        <div class="d-flex px-3 align-items-center">
            <legend class="text-black px-2 mt-2 mb-0">
                <div class="d-flex align-items-center">
                    <small class="d-block">{{ __($title ?? '') }}</small>

                    @if($export)
                        <a href="#" class="ms-auto px-2 text-muted" data-action="calendar#export" title="{{ __('Export') }}">
                            <x-orchid-icon path="bs.cloud-arrow-down"/>
                        </a>
                    @endif
                </div>

                @empty(!$description)
                    <p class="small text-muted mb-0 content-read">
                        {!! __($description  ?? '') !!}
                    </p>
                @endempty
            </legend>
        </div>

        <div class="position-relative w-100">
            <figure id="{{$slug}}" class=".calendar w-100 h-full m-0 p-0 d-flex"></figure>
        </div>
    </div>
</div>
