@component($typeForm, get_defined_vars())
    <div data-controller="input"
         data-input-mask="{{$mask ?? ''}}"
    >
        <input {{ $attributes }}>
    </div>

    @if($relativeTime)
        <relative-time format="elapsed" datetime="{{ $timestamp }}" title="{{ $parsedTime }}"></relative-time>
    @else
        @empty(!$datalist)
            <datalist id="datalist-{{$name}}">
                @foreach($datalist as $item)
                    <option value="{{ $item }}">
                @endforeach
            </datalist>
        @endempty
    @endif


@endcomponent
