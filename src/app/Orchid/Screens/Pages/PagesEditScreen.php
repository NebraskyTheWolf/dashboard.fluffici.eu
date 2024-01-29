<?php

namespace App\Orchid\Screens\Pages;

use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use App\Models\Pages;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UpdateAudit;

class PagesEditScreen extends Screen
{

    var $pages;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Pages $pages): iterable
    {
        return [
            'pages' => $pages
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->pages->exists ? __('pages.screen.edit.title') : __('pages.screen.edit.title.create');
    }

    public function permission(): iterable
    {
        return [
            'platform.systems.pages.read'
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('pages.screen.edit.button.create_page'))
                ->icon('bs.pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->pages->exists),

            Button::make(__('pages.screen.edit.button.update'))
                ->icon('bs.note')
                ->method('createOrUpdate')
                ->canSee($this->pages->exists),

            Button::make(__('pages.screen.edit.button.remove'))
                ->icon('bs.trash')
                ->confirm(__('common.modal.confirm'))
                ->method('remove')
                ->canSee($this->pages->exists),
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
                Group::make([
                    Input::make('pages.page_slug')
                        ->title(__('pages.screen.input.slug.title'))
                        ->placeholder(__('pages.screen.input.slug.placeholder'))
                        ->help(__('pages.screen.input.slug.help')),

                    Input::make('pages.title')
                        ->title(__('pages.screen.input.title.title'))
                        ->placeholder(__('pages.screen.input.title.placeholder'))
                        ->help(__('pages.screen.input.title.help')),
                ]),

                Quill::make('pages.content')
                    ->title(__('pages.screen.input.content.title')),
            ])->title(__('pages.screen.group.title')),
        ];
    }

    public function createOrUpdate(Request $request) {
        if (!isset($this->pages->visits)) {
            $this->pages->visits = 0;
        }

        $this->pages->fill($request->get('pages'))->save();

        Toast::info(__('screen.toast.created', ['name' => $this->pages->page_slug]));

        event(new UpdateAudit("page_updated", "Updated " . $this->pages->page_slug, Auth::user()->name));

        return redirect()->route('platform.pages.list');
    }

    public function remove() {
        $this->pages->delete();

        Toast::info(__('screen.toast.deleted', ['name' => $this->pages->page_slug]));

        event(new UpdateAudit("page_deleted", "Deleted " . $this->pages->page_slug, Auth::user()->name));

        return redirect()->route('platform.pages.list');
    }
}
