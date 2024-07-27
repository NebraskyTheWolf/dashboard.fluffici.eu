<?php

namespace App\Orchid\Layouts\Event;

use App\Models\Event\Events;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EventListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'events';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', __('events.table.name'))
                ->render(function (Events $event) {
                    return Link::make($event->name)
                        ->icon('bs.pencil')
                        ->route('platform.events.edit', $event);
                }),

            TD::make('status', __('events.table.status'))
                ->render(function (Events $event) {
                    return "<span>" . $event->status . "</span>";
                }),

            TD::make('begin_at', __('events.table.begin_at'))
                ->render(function (Events $event) {
                    return Carbon::parse($event->begin)->diffForHumans();
                }),

            TD::make('end_at', __('events.table.end_at'))
                ->render(function (Events $event) {
                    return Carbon::parse($event->end)->diffForHumans();
                }),

            TD::make('created_at', __('events.table.created_at'))
                ->render(function (Events $event) {
                    return $event->created_at->diffForHumans();
                }),
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.clipboard2-minus';
    }

    protected function textNotFound(): string
    {
        return 'No event planned';
    }
}
