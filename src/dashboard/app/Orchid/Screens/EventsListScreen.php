<?php

namespace App\Orchid\Screens;

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
        return 'Events';
    }

    public function description(): ?string
    {
        return "All the upcoming and past events.";
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
            Link::make('Create new')
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
