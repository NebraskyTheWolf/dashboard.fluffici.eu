<?php

namespace App\Orchid\Screens\Events;

use Orchid\Screen\Screen;
use App\Models\Events;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Layouts\View;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Map;
use Ramsey\Uuid\Uuid;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;

use App\Events\UpdateAudit;

use App\Models\PlatformAttachments;

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
            'events' => $events
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

    public function permission(): iterable
    {
        return [
            'platform.systems.events.write'
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
            Button::make('Create event')
                ->icon('bs.pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->events->exists),

            Button::make('Update')
                ->icon('bs.note')
                ->method('createOrUpdate')
                ->canSee($this->events->exists && $this->events->status == "INCOMING"),

            Button::make('Cancel event')
                ->icon('bs.trash')
                ->method('cancel')
                ->canSee($this->events->exists && $this->events->status == "INCOMING")
                ->type(Color::DANGER),
            
            Button::make('Undo')
                ->icon('bs.arrow-clockwise')
                ->method('undo')
                ->canSee($this->events->exists && $this->events->status == "ENDED")
                ->type(Color::INFO),

            Button::make('Set as finished.')
                ->icon('bs.check-lg')
                ->method('finish')
                ->canSee($this->events->exists && $this->events->status == "INCOMING")
                ->type(Color::SUCCESS),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        if ($this->events->event_id == NULL) {
            $this->events->event_id = Uuid::uuid4();
        }

        return [
            Layout::rows([
                Group::make([
                    Input::make('events.name')
                        ->title('Event name')
                        ->placeholder('Attractive but mysterious name')
                        ->help('Specify a short descriptive title for this event.')
                        ->disabled($this->events->status == "CANCELLED"),

                    Quill::make('events.descriptions')
                        ->title('Specify a short descriptive content for this event.')
                        ->canSee(!$this->events->exists || ($this->events->exists && $this->events->status == "INCOMING")),

                    Select::make('events.type')
                        ->options([
                            'PHYSICAL'   => 'Physical',
                            'ONLINE' => 'Online',
                        ])
                        ->title('Event type')
                        ->help('Select the correct event type')
                        ->disabled($this->events->status == "CANCELLED")
                ]),

                Group::make([
                    DateTimer::make('events.begin')
                        ->title('The date of the begining.')
                        ->disabled($this->events->status == "CANCELLED"),
                
                    DateTimer::make('events.end')
                        ->title('The date of the ending.')
                        ->disabled($this->events->status == "CANCELLED"),
                ]),
            
                Group::make([
                    Map::make('events.min')
                        ->title('Select the first enplacement.')
                        ->help('Enter the coordinates, or use the search'),
                    Map::make('events.max')
                        ->title('Select the second enplacement.')
                        ->help('Enter the coordinates, or use the search')
                ]),

                Group::make([
                    Input::make('events.link')
                        ->title('Link')
                        ->placeholder('https://owo.com')
                        ->help('Specify the link of the event')
                        ->disabled($this->events->status == "CANCELLED"),
                ]),

                Cropper::make('events.banner')
                    ->title('Upload a banner or let it blank :3')
                    ->actionId($this->events->event_id)
                    ->remoteTag('banners')
                    ->minWidth(750)
                    ->maxWidth(1500)
                    ->minHeight(250)
                    ->maxHeight(500)
                    ->maxFileSize(200)                
                
            ])->title('Event informations'),
        ];
    }

    public function createOrUpdate(Request $request) {
        
        $this->events->fill($request->get('events'))->save();

        Toast::info('You have successfully created a new event.');

        event(new UpdateAudit("event", $this->events->name . " updated.", Auth::user()->name));

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

        event(new UpdateAudit("event", $this->events->name . " set a cancelled.", Auth::user()->name));

        return redirect()->route('platform.events.list');
    }

    public function finish() {
        
        Events::updateOrCreate(
            ['event_id' => $this->events->event_id],
            [
                'status' => "ENDED"
            ]
        );

        Toast::info('You have successfully finished ' . $this->events->name);

        event(new UpdateAudit("event", $this->events->name . " set a finished.", Auth::user()->name));

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

        event(new UpdateAudit("event", "Undone last changes " . $this->events->name, Auth::user()->name));

        return redirect()->route('platform.events.list');
    }

    private function getBanner($id) {
        if ($this->events->exists) {
            return PlatformAttachments::where('action_id', $id)->firstOrFail()->attachment_id ?: NULL;
        } else {
            return NULL;
        }
    }
}
