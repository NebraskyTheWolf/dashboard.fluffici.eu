@component($typeForm, get_defined_vars())
    <div class="border-dashed text-end p-3 picture-actions" >
        <img 
            src="{{$autumnUrl}}/{{$bucket}}/{{$objectId}}/?width={{$width}}&height={{$height}}" 
            alt="{{$objectId}}" 
            aria-readonly="{{$readOnly}}"
            style="display: block; margin-left: auto;  margin-right: auto;">
    </div>
@endcomponent
