<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Events;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Map;
use Ramsey\Uuid\Uuid;
use Orchid\Support\Facades\Toast;

class EventsEditScreen extends Screen
{

    public $events;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Events $events): iterable
    {
        return [
            'events' => $events,
            'events.min' => [
                'lat' => 50.0596288,
                'lng' => 14.446459273258009,
            ],
            'events.max' => [
                'lat' => 50.0596288,
                'lng' => 14.446459273258009,
            ]
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->events->exists ? 'Edit event' : 'Creating a new event';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create event')
                ->icon('bs.pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->events->exists),

            Button::make('Update')
                ->icon('bs.note')
                ->method('createOrUpdate')
                ->canSee($this->events->exists),

            Button::make('Cancel event')
                ->icon('bs.trash')
                ->method('cancel')
                ->canSee($this->events->exists && $this->events->status == "INCOMING"),
            
            Button::make('Undo')
                ->icon('bs.arrow-clockwise')
                ->method('undo')
                ->canSee($this->events->exists && $this->events->status == "ENDED"),

            Button::make('Set as finished.')
                ->icon('bs.check-lg')
                ->method('finish')
                ->canSee($this->events->exists && $this->events->status == "INCOMING"),
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
                    Input::make('events.name')
                        ->title('Event name')
                        ->placeholder('Attractive but mysterious name')
                        ->help('Specify a short descriptive title for this event.'),

                    Quill::make('events.descriptions')
                        ->title('Specify a short descriptive content for this event.'),

                    Select::make('events.type')
                        ->options([
                            'PHYSICAL'   => 'Physical',
                            'ONLINE' => 'Online',
                        ])
                        ->title('Event type')
                        ->help('Select the correct event type')
                ]),

                Group::make([
                    DateTimer::make('events.begin')
                        ->title('The date of the begining.'),
                
                    DateTimer::make('events.end')
                        ->title('The date of the ending.'),
                ]),
            
                Group::make([
                    Map::make('events.min')
                        ->title('Select the first enplacement.')
                        ->help('Enter the coordinates, or use the search'),
                    Map::make('events.max')
                        ->title('Select the second enplacement.')
                        ->help('Enter the coordinates, or use the search'),
                ]),

                Group::make([
                    Input::make('events.link')
                        ->title('Link')
                        ->placeholder('https://owo.com')
                        ->help('Specify the link of the event'),
                ]),

                Upload::make('events.banner')
                    ->title('Upload a banner or let it blank :3')
                    ->method('bannerUpload')
                    ->closeOnAdd()
                    ->horizontal()
                    ->title('Event Banner')
            ])->title('Event informations')
        ];
    }

    public function createOrUpdate(Request $request) {
        $this->events->event_id = Uuid::uuid4();
        
        $this->events->fill($request->get('events'))->save();

        Toast::info('You have successfully created a new event.');

        return redirect()->route('platform.events.list');
    }

    public function cancel() {
        
        Events::updateOrCreate(
            ['event_id' => $this->events->event_id],
            [
                'status' => "CANCELLED"
            ]
        );

        Toast::info('You have successfully cancelled ' . $this->events->name);

        return redirect()->route('platform.events.list');
    }

    public function finish() {
        
        Events::updateOrCreate(
            ['event_id' => $this->events->event_id],
            [
                'status' => "ENDED"
            ]
        );

        Toast::info('You have successfully cancelled ' . $this->events->name);

        return redirect()->route('platform.events.list');
    }

    public function undo() {
        Events::updateOrCreate(
            ['event_id' => $this->events->event_id],
            [
                'status' => "INCOMING"
            ]
        );

        Toast::info('You have successfully undone the last changes on ' . $this->events->name);

        return redirect()->route('platform.events.list');
    }

    public function bannerUpload(Request $request) {
        Toast::info(' ' . $request->file('events.banner'));
    }
}
