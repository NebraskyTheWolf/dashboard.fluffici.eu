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
        return $this->post->exists ? 'Edit post' : 'Creating a new post';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "News";
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
            Button::make('Create post')
                ->icon('bs.pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->post->exists),

            Button::make('Update')
                ->icon('bs.note')
                ->method('createOrUpdate')
                ->canSee($this->post->exists),

            Button::make('Remove')
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
                    'Post Information' => [
                        Layout::rows([
                            Input::make('post.title')
                                ->title('Title')
                                ->placeholder('Attractive but mysterious title')
                                ->help('Specify a short descriptive title for this post.')
                                ->disabled($this->post->exists),

                            TextArea::make('post.description')
                                ->title('Description')
                                ->rows(3)
                                ->maxlength(200)
                                ->placeholder('Brief description for preview')
                                ->disabled($this->post->exists),

                            Relation::make('post.author')
                                ->title('Author')
                                ->fromModel(User::class, 'name')
                                ->disabled($this->post->exists),

                            Quill::make('post.body')
                                ->title('Main text')
                                ->disabled($this->post->exists),
                        ])
                    ],
                    'Statistics' => [
                        ShopProfit::make('likes', 'Overall statistics until now'),
                    ],
                    'Comments' => new PostCommentLayout('partials.comments', [
                        'comments' => PostsComments::where('post_id', $this->post->id)->paginate(),
                        'postId' => $this->post->post_id
                    ])
                ])->activeTab('Post Information')
            ];
        } else {
            return [
                Layout::tabs([
                    'Post Information' => [
                        Layout::rows([
                            Input::make('post.title')
                                ->title('Title')
                                ->placeholder('Attractive but mysterious title')
                                ->help('Specify a short descriptive title for this post.'),

                            TextArea::make('post.description')
                                ->title('Description')
                                ->rows(3)
                                ->maxlength(200)
                                ->placeholder('Brief description for preview'),

                            Relation::make('post.author')
                                ->title('Author')
                                ->fromModel(User::class, 'name'),

                            Quill::make('post.body')
                                ->title('Main text')
                        ])
                    ],
                    'Statistics' => [],
                    'Comments' => []
                ])->activeTab('Post Information')
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

        Toast::info('You have successfully created a post.');

        event(new UpdateAudit("post", "Updated " . $this->post->title, Auth::user()->name));

        return redirect()->route('platform.post.list');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $this->post->delete();

        Toast::info('You have successfully deleted the post.');

        event(new UpdateAudit("post_removed", "Removed " . $this->post->title, Auth::user()->name));

        return redirect()->route('platform.post.list');
    }
}
