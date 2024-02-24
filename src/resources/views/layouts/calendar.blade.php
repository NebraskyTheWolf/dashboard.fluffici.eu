<div
    data-controller="calendar"
    data-calendar-parent="#{{$slug}}"
    data-calendar-editable="{{$editable}}"
    data-calendar-color="{{$color}}"
    data-calendar-locale="{{$locale}}"
    data-calendar-initial-view="{{$initialView}}"
    data-calendar-google-calendar="{{$googleCalendar}}"
    data-calendar-google-calendar-secret="{{$googleCalendarSecret}}"
    data-calendar-google-calendar-url="{{$googleCalendarURL}}"
>
    <div class="bg-white rounded shadow-sm mb-3 pt-3">
        <div class="position-relative w-100">
            <div id='calendar' class=".calendar w-100 h-full m-0 p-0 d-flex"></div>
        </div>
    </div>
</div>
