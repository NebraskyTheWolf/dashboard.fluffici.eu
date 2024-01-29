<?php

namespace App\Orchid\Layouts;

use App\Models\Post;
use App\Models\PostsLikes;
use App\Models\PostsComments;
use App\Models\User;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class PostListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'posts';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', __('posts.table.title'))
                ->render(function (Post $post) {
                    return Link::make($post->title)
                        ->icon('bs.pencil')
                        ->route('platform.post.edit', $post);
                }),

            TD::make('author', __('posts.table.author'))
                ->render(function (Post $post) {
                    return User::where('id', $post->author)->firstOrFail()->name;
                }),

            TD::make('likes', __('posts.table.likes'))
                ->render(function (Post $post) {
                    return PostsLikes::where('post_id', $post->id)->count() ?: 0;
                }),

            TD::make('comments', __('posts.table.comments'))
                ->render(function (Post $post) {
                    return PostsComments::where('post_id', $post->id)->count();
                }),

            TD::make('created_at', __('posts.table.created_at'))
                ->render(function (Post $post) {
                    return $post->created_at->diffForHumans();
                }),

            TD::make('updated_at', __('posts.table.updated_at'))
                ->render(function (Post $post) {
                    return $post->updated_at->diffForHumans();
                }),
        ];
    }
}
