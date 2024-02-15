<?php

namespace App\Orchid\Screens\Events;

use App\Events\UpdateAudit;
use App\Models\Events;
use App\Models\PlatformAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Map;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Ramsey\Uuid\Uuid;

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
        return $this->events->exists ? __('events.screen.edit.title') : __('events.screen.edit.title.create');
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
            Button::make(__('events.screen.edit.button.create'))
                ->icon('bs.pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->events->exists),

            Button::make(__('events.screen.edit.button.update'))
                ->icon('bs.note')
                ->method('createOrUpdate')
                ->canSee($this->events->exists)
                ->canSee(!$this->events->status == "INCOMING"),

            Button::make(__('events.screen.edit.button.cancel'))
                ->icon('bs.trash')
                ->confirm(__('common.modal.event.cancel'))
                ->method('cancel')
                ->canSee($this->events->exists)
                ->canSee(!$this->events->status == "INCOMING")
                ->type(Color::DANGER),

            Button::make(__('events.screen.edit.button.undo'))
                ->icon('bs.arrow-clockwise')
                ->method('undo')
                ->canSee($this->events->exists)
                ->canSee(!$this->events->status == "ENDED")
                ->type(Color::INFO),

            Button::make(__('events.screen.edit.button.finish'))
                ->icon('bs.check-lg')
                ->method('finish')
                ->canSee($this->events->exists)
                ->canSee(!$this->events->status == "FINISHED")
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
                        ->title(__('events.screen.input.event_name.title'))
                        ->placeholder(__('events.screen.input.event_name.placeholder'))
                        ->help(__('events.screen.input.event_name.help'))
                        ->disabled($this->events->status == "CANCELLED"),

                    Quill::make('events.descriptions')
                        ->title(__('events.screen.input.description.title'))
                        ->canSee(!$this->events->exists || ($this->events->exists && $this->events->status == "INCOMING")),

                    Select::make('events.type')
                        ->options([
                            'PHYSICAL'   => __('events.screen.input.type.choice.one'),
                            'ONLINE' => __('events.screen.input.type.choice.two'),
                        ])
                        ->title(__('events.screen.input.type.title'))
                        ->help(__('events.screen.input.type.help'))
                        ->disabled($this->events->status == "CANCELLED")
                ]),

                Group::make([
                    DateTimer::make('events.begin')
                        ->title(__('events.screen.input.begin.title'))
                        ->disabled($this->events->status == "CANCELLED"),

                    DateTimer::make('events.end')
                        ->title(__('events.screen.input.end.title'))
                        ->disabled($this->events->status == "CANCELLED"),
                ]),

                Group::make([
                    Map::make('events.min')
                        ->title(__('events.screen.input.map_min.title'))
                        ->help(__('events.screen.input.map_min.help')),
                    Map::make('events.max')
                        ->title(__('events.screen.input.map_max.title'))
                        ->help(__('events.screen.input.map_max.help'))
                ]),

                Group::make([
                    Input::make('events.link')
                        ->title(__('events.screen.input.link.title'))
                        ->placeholder(__('events.screen.input.link.placeholder'))
                        ->help(__('events.screen.input.link.help'))
                        ->disabled($this->events->status == "CANCELLED"),
                ]),

                Cropper::make('events.banner')
                    ->title(__('events.screen.input.banner.title'))
                    ->actionId($this->events->event_id)
                    ->remoteTag('banners')
                    ->minWidth(750)
                    ->maxWidth(1500)
                    ->minHeight(250)
                    ->maxHeight(500)
                    ->maxFileSize(200)

            ])->title(__('events.screen.group.title')),
        ];
    }

    public function createOrUpdate(Request $request) {

        $this->events->fill($request->get('events'))->save();

        Toast::info(__('events.screen.toast.created'));

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

        Toast::info(__('events.screen.toast.cancel', ['name' => $this->events->name]));


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

        Toast::info(__('events.screen.toast.finish', ['name' => $this->events->name]));


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

        Toast::info(__('events.screen.toast.undo', ['name' => $this->events->name]));

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
