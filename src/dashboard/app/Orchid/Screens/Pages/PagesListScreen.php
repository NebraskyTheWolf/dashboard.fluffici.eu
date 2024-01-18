<?php

namespace App\Orchid\Screens\Pages;

use Orchid\Screen\Screen;
use App\Orchid\Layouts\PagesListLayout;
use Orchid\Screen\Actions\Link;

use App\Models\Pages;


class PagesListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'pages' => Pages::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Pages';
    }

    public function description(): ?string
    {
        return "All the active pages.";
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
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.pages.edit')
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
            PagesListLayout::class
        ];
    }
}
