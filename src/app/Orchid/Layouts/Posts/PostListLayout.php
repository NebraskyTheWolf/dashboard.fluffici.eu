<?php

namespace App\Orchid\Layouts\Posts;

use App\Models\Posts\Post;
use App\Models\Posts\PostsComments;
use App\Models\Posts\PostsLikes;
use App\Models\Security\Account\User;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

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

    protected function iconNotFound(): string
    {
        return 'bs.paperclip';
    }

    protected function textNotFound(): string
    {
        return 'No post yet.';
    }

    protected function subNotFound(): string
    {
        return 'You can publish a new post on the website.';
    }
}
