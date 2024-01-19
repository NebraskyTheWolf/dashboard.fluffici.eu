@component($typeForm, get_defined_vars())
    <div data-controller="attach"
        class="attach"
        data-attach-name-value="{{ $name }}"
        data-attach-size-value="{{ $maxSize }}"
        data-attach-count-value="{{ $maxCount }}"
        data-attach-loading-value="0"
        data-attach-attachment-value='@json($value ?? [])'
    >
            <div data-target="attach.preview" class="row row-cols-5 gy-3 horizontal">
                <div class="col" data-attach-target="container">
                    <label for="{{$id}}" class="border rounded bg-light attach-image-placeholder pointer-event">
                        <input class="form-control d-none"
                            type="file"
                            multiple
                            data-attach-target="files"
                            data-action="change->attach#change"
                            {{ $attributes }}
                        >
    
                        <span class="d-block text-center fw-normal small text-muted p-3 mx-auto">
                        <span class="choose d-flex align-items-center">
                                <x-orchid-icon path="bs.cloud-arrow-up" class="h3"/>
                                <small class="text-muted d-block ms-2">{{ __($placeholder) }}</small>
                        </span>
    
                        <span class="spinner-border" role="status">
                            <span class="visually-hidden">{{ __('Loading...') }}</span>
                        </span>
                    </span>
                    </label>

                    <input type="text" name="bucket" value="{{ $remoteTag }}" id="bucket" hidden>
                    <input type="text" name="filename" value="{{ $name }}" id="filename" hidden>
                    <input type="number" name="user_id" value="{{ Auth::id() }}" id="user_id" hidden>
                    <input type="text" name="action_id" value="{{ $actionId ?: 0 }}" id="action_id" hidden>
                    <input type="text" name="avatar_id" value="{{ $objectId ?: null }}" id="avatar_id" hidden>
                </div>
            </div>
        </div>

@endcomponent