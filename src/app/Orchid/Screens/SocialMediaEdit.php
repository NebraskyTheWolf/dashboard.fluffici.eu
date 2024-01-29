<?php

namespace App\Orchid\Screens;

use App\Events\UpdateAudit;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class SocialMediaEdit extends Screen
{

    public $social;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(\App\Models\SocialMedia $social): iterable
    {
        return [
            'social' => $social
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->social->exists ? 'Edit social media' : 'Create social media';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.pencil')
                ->method('createOrUpdate'),
            Button::make('Delete')
                ->icon('bs.trash')
                ->confirm(__('common.modal.confirm'))
                ->method('remove')
                ->canSee($this->social->exists)
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                \Orchid\Screen\Fields\Group::make([
                    Input::make('social.slug')
                        ->title('Slug')
                        ->placeholder('Enter the name that the system will use.'),
                    Input::make('social.url')
                        ->title('URL')
                        ->placeholder('Enter the redirect url to your social media.')
                ])
            ])->title('Information')
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->social->fill($request->get('social'))->save();

        Toast::info('You added a new social media.');

        event(new UpdateAudit('social_media', 'Updated ' . $this->social->slug, Auth::user()->name));

        return redirect()->route('platform.social.list');
    }

    public function remove()
    {
        $this->social->delete();

        Toast::info('You delete ' . $this->social->slug);

        event(new UpdateAudit('social_media', 'Deleted ' . $this->social->slug, Auth::user()->name));

        return redirect()->route('platform.social.list');
    }
}
