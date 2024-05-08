@php use App\Models\User;use Carbon\Carbon; @endphp

<div class="ui container center" style="bottom: 50%; padding: 35px">
    <div class="ui comments">
        @foreach ($comments as $comment)
            <div class="comment-card" id="comment-{{ $comment->id }}">
                <div class="comment-avatar">
                    @if (User::where('id', $comment->author)->first()->avatar_id !== null)
                        <img src="https://autumn.fluffici.eu/avatars/{{ User::where('id', $comment->author)->first()->avatar_id }}" alt="{{ $comment->author }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ User::where('id', $comment->author)->first()->name }}&background=0D8ABC&color=fff" alt="{{ $comment->author }}">
                    @endif
                </div>
                <div class="comment-content">
                    <div class="comment-author">{{ User::where('id', $comment->author)->first()->name }}</div>
                    <div class="comment-metadata">{{ Carbon::parse($comment->created_at)->diffForHumans() }}</div>
                    <div class="comment-text" id="comment-message-{{$comment->id}}">
                        {{ $comment->message }}
                    </div>
                </div>
                <div class="comment-actions">
                    <div class="comment-actions">
                        <div class="comment-action" id="delete" style="color: orangered;"><x-orchid-icon path="bs.trash"/></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
