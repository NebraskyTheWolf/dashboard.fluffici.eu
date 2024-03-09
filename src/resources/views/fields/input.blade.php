@component($typeForm, get_defined_vars())
    <div data-controller="input"
         data-input-mask="{{$mask ?? ''}}"
    >

        @if($relativeTime)
            <relative-time format="elapsed" datetime="{{ $timestamp }}" title="{{ $timestamp }}"></relative-time>
        @else
            <input {{ $attributes }}>
        @endif
    </div>

    @empty(!$datalist)
        <datalist id="datalist-{{$name}}">
            @foreach($datalist as $item)
                <option value="{{ $item }}">
            @endforeach
        </datalist>
    @endempty

@endcomponent
