<?php

namespace App\Orchid\Screens\Posts;


use App\Models\Post;
use App\Models\PostsComments;
use App\Models\PostsLikes;
use App\Orchid\Layouts\PostCommentLayout;
use App\Models\User;
use App\Orchid\Layouts\ShopProfit;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;
use App\Events\UpdateAudit;

class PostEditScreen extends Screen
{
    /**
     * @var Post
     */
    public $post;

    public $posts_comments;

    /**
     * Query data.
     *
     * @param Post $post
     *
     * @return array
     */
    public function query(Post $post): array
    {
        return [
            'post' => $post,
            'posts_comments' => PostsComments::where('post_id', $post->id)->paginate(),
            'likes' => [
                PostsLikes::where('post_id', $post->id)->sumByDays('post_id')->toChart('Likes'),
                PostsComments::where('post_id', $post->id)->sumByDays('post_id')->toChart('Comments')
            ]
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return $this->post->exists ? __('posts.screen.edit.title') : __('posts.screen.edit.title.create');
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return __('posts.screen.edit.descriptions');
    }

    public function permission(): iterable
    {
        return [
            'platform.systems.post.write'
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
            Button::make(__('posts.screen.edit.button.create'))
                ->icon('bs.pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->post->exists),

            Button::make(__('posts.screen.edit.button.update'))
                ->icon('bs.note')
                ->method('createOrUpdate')
                ->canSee($this->post->exists),

            Button::make(__('posts.screen.edit.button.remove'))
                ->icon('bs.trash')
                ->method('remove')
                ->canSee($this->post->exists),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        if ($this->post->exists) {
            return [
                Layout::tabs([
                    __('posts.screen.tabs.post_information') => [
                        Layout::rows([
                            Input::make('post.title')
                                ->title(__('posts.screen.input.post_title.title'))
                                ->placeholder(__('posts.screen.edit.input.post_title.placeholder'))
                                ->help(__('posts.screen.edit.input.post_title.help'))
                                ->disabled($this->post->exists),

                            TextArea::make('post.description')
                                ->title(__('posts.screen.input.description.title'))
                                ->rows(3)
                                ->maxlength(200)
                                ->placeholder(__('posts.screen.input.description.placeholder'))
                                ->disabled($this->post->exists),

                            Relation::make('post.author')
                                ->title(__('posts.screen.input.author.title'))
                                ->fromModel(User::class, 'name')
                                ->disabled($this->post->exists),

                            Quill::make('post.body')
                                ->title(__('posts.screen.input.body.title'))
                                ->disabled($this->post->exists),
                        ])
                    ],
                    __('posts.screen.tabs.statistics') => [
                        ShopProfit::make('likes', __('posts.screen.chart.likes.title')),
                    ],
                    __('posts.screen.tabs.comments') => new PostCommentLayout('partials.comments', [
                        'comments' => PostsComments::where('post_id', $this->post->id)->paginate(),
                        'postId' => $this->post->post_id
                    ])
                ])->activeTab( __('posts.screen.tabs.post_information'))
            ];
        } else {
            return [
                Layout::tabs([
                    __('screen.tabs.post_information') => [
                        Layout::rows([
                            Input::make('post.title')
                                ->title(__('posts.screen.input.post_title.title'))
                                ->placeholder(__('posts.screen.input.post_title.placeholder'))
                                ->help(__('posts.screen.input.post_title.help')),

                            TextArea::make('post.description')
                                ->title(__('posts.screen.input.description.title'))
                                ->rows(3)
                                ->maxlength(200)
                                ->placeholder(__('posts.screen.input.description.placeholder')),

                            Relation::make('post.author')
                                ->title(__('posts.screen.input.author.title'))
                                ->fromModel(User::class, 'name'),

                            Quill::make('post.body')
                                ->title(__('posts.screen.input.body.title'))
                        ])
                    ],
                    __('posts.screen.tabs.statistics') => [],
                    __('posts.screen.tabs.comments') => []
                ])->activeTab(__('posts.screen.tabs.post_information'))
            ];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $this->post->fill($request->get('post'))->save();

        Toast::info(__('posts.screen.toast.created'));

        event(new UpdateAudit("post", "Updated " . $this->post->title, Auth::user()->name));

        return redirect()->route('platform.post.list');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $this->post->delete();

        Toast::info(__('posts.screen.toast.removed', ['title' => $this->post->title]));

        event(new UpdateAudit("post_removed", "Removed " . $this->post->title, Auth::user()->name));

        return redirect()->route('platform.post.list');
    }
}
