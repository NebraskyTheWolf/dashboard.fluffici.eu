<div class="mb-3">
    @isset($title)
        <legend class="text-black px-4 mb-0">
            {{ __($title) }}
        </legend>
    @endisset
    <div class="row mb-2 g-3 g-mb-4">
        @foreach($metrics as $key => $metric)
            <div class="col">
                <div class="p-4 bg-white rounded shadow-sm h-100 d-flex flex-column">
                    <small class="text-muted d-block mb-1">{{ $key }}</small>

                    @if($metric['value'] <= 0)
                        <p class="h3 text-red-50 fw-light mt-auto" id="{{  $metric['key'] }}">
                            {{ $metric['value'] }}
                        </p>
                    @else
                        <p class="h3 text-green-700 fw-light mt-auto" id="{{  $metric['key'] }}">
                            + {{ $metric['value'] }}
                        </p>
                    @endif

                </div>
            </div>
        @endforeach
    </div>
</div>
