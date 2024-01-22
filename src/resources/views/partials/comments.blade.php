<div class="ui container center" style="bottom: 50%">
    <div class="ui comments">
        @foreach ($comments as $comment)
            <div class="comment" id="comment-{{  $comment->id  }}">
                <a class="avatar">
                    <img src="https://ui-avatars.com/api/?name={{ $comment->author }}&background=0D8ABC&color=fff" alt="{{ $comment->author }}">
                </a>
                <div class="content">
                    <a class="author"></a>
                    <div class="metadata">
                        <div class="date">{{\Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</div>
                    </div>
                    <div class="text" id="comment-message-{{$comment->id}}">
                        {{  $comment->message  }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
