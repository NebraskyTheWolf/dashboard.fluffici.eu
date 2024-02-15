<div class="mb-3">
    @isset($title)
        <legend class="text-black px-4 mb-0">
            {{ $title }}
        </legend>
    @endisset
    <div class="row mb-2 g-3 g-mb-4">
        @foreach($metrics as $key => $metric)
            <div class="col">
                <div class="p-4 bg-white rounded shadow-sm h-100 d-flex flex-column">
                    <small class="text-muted d-block mb-1">{{ $key }}</small>

                    @if($metric['value'] < 0)
                        <a class="h3 fw-light mt-auto text-primary" id="{{  $metric['key'] }}">
                            {{ $metric['value'] }}
                        </a>
                    @else
                        <a class="h3 fw-light mt-auto text-success" id="{{  $metric['key'] }}">
                            + {{ $metric['value'] }}
                        </a>
                    @endif

                    @if(isset($metric['diff']) && (float)$metric['diff'] !== 0.0)
                        <small class="small {{ (float)$metric['diff'] < 0 ? 'text-primary': 'text-success' }}">
                            {{ round($metric['diff'], 2) }} %
                        </small>
                    @endif

                </div>
            </div>
        @endforeach
    </div>
</div>
