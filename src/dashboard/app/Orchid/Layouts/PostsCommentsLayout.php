<?php

namespace App\Orchid\Layouts;

use App\Models\Post;
use App\Models\PostsLikes;
use App\Models\PostsComments;
use App\Models\User;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class PostsCommentsLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'posts_comments';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('author', 'Author'),
            
            TD::make('message', "Comment"),
            
            TD::make('created_at', 'Created')
                ->render(function (Post $post) {
                    return $post->created_at->diffForHumans();
                })
        ];
    }
}