<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use App\Models\Pages;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;

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
        return $this->pages->exists ? 'Edit page' : 'Creating a new page';
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
            Button::make('Create page')
                ->icon('bs.pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->pages->exists),

            Button::make('Update')
                ->icon('bs.note')
                ->method('createOrUpdate')
                ->canSee($this->pages->exists),

            Button::make('Remove')
                ->icon('bs.trash')
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
                        ->title('Page slug')
                        ->placeholder('Add the name related to the page')
                        ->help('This slug would be used to access the page.'),
                    Input::make('pages.title')
                        ->title('Page title')
                        ->placeholder('Attractive but mysterious title')
                        ->help('Specify a short descriptive title for this event.'),
                ]),
                
                Quill::make('pages.content')
                    ->title('Specify the content of the page here.'),
            ])->title("Create a new page"),
        ];
    }

    public function createOrUpdate(Request $request) {
        if (!isset($this->pages->visits)) {
            $this->pages->visits = 0;
        }

        $this->pages->fill($request->get('pages'))->save();

        Toast::info('You have successfully created ' . $this->pages->page_slug);

        return redirect()->route('platform.pages.list');
    }

    public function remove() {
        $this->pages->delete();

        Toast::info('You have successfully deleted ' . $this->pages->page_slug);

        return redirect()->route('platform.pages.list');
    }
}
