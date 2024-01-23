<?php

namespace App\Orchid\Screens\Events;

use Orchid\Screen\Screen;
use App\Models\Events;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\EventListLayout;


class EventsListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'events' => Events::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('events.screen.title');
    }

    public function description(): ?string
    {
        return __('events.screen.descriptions');
    }

    public function permission(): iterable
    {
        return [
            'platform.systems.events.read'
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
            Link::make(__('events.screen.button.create_new'))
                ->icon('pencil')
                ->route('platform.events.edit')
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
            EventListLayout::class
        ];
    }
}
