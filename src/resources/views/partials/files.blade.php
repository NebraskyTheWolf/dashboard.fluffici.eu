<div class="ui three column grid">

    @if (empty($files))
        <div class="ui icon error message">
            <i class="notched user icon"></i>
            <div class="content">
                <div class="header">
                    No files.
                </div>
                <p>There is nothing to see yet.</p>
            </div>
        </div>
    @endif


    @foreach($files as $file)
        <div class="column">
            <div class="ui segment">
                <img src="https://autumn.rsiniya.uk/{{$file->bucket}}/{{$file->attachment_id}}" alt="{{$file->attachment_id}}">
            </div>
        </div>
    @endforeach

</div>
