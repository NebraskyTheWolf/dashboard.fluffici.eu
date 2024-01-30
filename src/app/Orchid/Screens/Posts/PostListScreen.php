<?php

namespace App\Orchid\Screens\Posts;

use App\Models\Post;
use App\Orchid\Layouts\Posts\PostListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class PostListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'posts' => Post::paginate()
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return __('posts.screen.title');
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return __('posts.screen.descriptions');
    }

    public function permission(): iterable
    {
        return [
            'platform.systems.post.read'
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('posts.screen.button.create_new'))
                ->icon('pencil')
                ->route('platform.post.edit')
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            PostListLayout::class
        ];
    }
}
