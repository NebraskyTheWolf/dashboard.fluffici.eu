<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\EventsInteresteds;
use Orchid\Screen\Actions\Link;

use Carbon\Carbon;

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
            TD::make('name', 'Name')
                ->render(function (Events $event) {
                    return Link::make($event->name)
                        ->route('platform.events.edit', $event);
                }),

            TD::make('status')
                ->render(function (Events $event) {
                    return "<span>" . $event->status . "</span>";
                }),

            TD::make('interested', "Interested peoples")
                ->render(function (Events $event) {
                    return EventsInteresteds::where('event_id', $event->id)->count() ?: 0;
                }),
            
            TD::make('begin_at', 'Begin in')
                ->render(function (Events $event) {
                    return Carbon::parse($event->begin)->diffForHumans();
                }),

            TD::make('begin_at', 'End in')
                ->render(function (Events $event) {
                    return Carbon::parse($event->end)->diffForHumans();
                }),
            
            TD::make('created_at', 'Created')
                ->render(function (Events $event) {
                    return $event->created_at->diffForHumans();
                }),
        ];
    }
}
