<?php

namespace App\Orchid\Screens\Events;

use App\Events\UpdateAudit;
use App\Mail\ScheduleMail;
use App\Models\Events;
use App\Models\PlatformAttachments;
use Httpful\Exception\ConnectionErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;
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
            'events' => $events,
            'metrics' => [
                'summary' => [],
                'temperature' => [],
                'wind' => [],
                'precipitation' => [],
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
                ->canSee($this->events->status == "INCOMING")
                ->canSee($this->events->status == "STARTED"),

            Button::make(__('events.screen.edit.button.cancel'))
                ->icon('bs.trash')
                ->confirm(__('common.modal.event.cancel'))
                ->method('cancel')
                ->canSee($this->events->exists)
                ->canSee($this->events->status == "INCOMING")
                ->canSee($this->events->status == "STARTED")
                ->type(Color::DANGER),

            Button::make(__('events.screen.edit.button.undo'))
                ->icon('bs.arrow-clockwise')
                ->method('undo')
                ->canSee($this->events->exists)
                ->canSee($this->events->status == "ENDED")
                ->type(Color::INFO),

            Button::make(__('events.screen.edit.button.finish'))
                ->icon('bs.check-lg')
                ->method('finish')
                ->canSee($this->events->exists)
                ->canSee($this->events->status == "INCOMING")
                ->canSee($this->events->status == "STARTED")
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
            Layout::tabs([
                'Information' => [
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
                ],
                'Weather' => []
            ])->activeTab('Information')
        ];
    }

    public function createOrUpdate(Request $request) {

        $this->events->fill($request->get('events'))->save();

        Toast::info(__('events.screen.toast.created'));

        event(new UpdateAudit("event", $this->events->name . " updated.", Auth::user()->name));

        if (env('APP_TEST_MAIL', false)) {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new ScheduleMail());
            }
        } else {
            Mail::to('vakea@fluffici.eu')->send(new ScheduleMail());
        }

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

    /**
     * Fetches the temperature from a remote API.
     *
     * @return float The temperature in Celsius, or 0.0 if the API request fails.
     * @throws ConnectionErrorException
     */
    private function getTemperature(): float
    {
        $response = $this->sendRequest('https://www.meteosource.com/api/v1/free/point?lat=' . $this->events->min['lat'] . '&lon=' . $this->events->min['lng'] .'&timezone=UTC&language=cs&units=metric');
        if ($response->code === 200) {
            return json_decode($response->body, true)['current']['temperature'];
        }

        return 0.0;
    }

    private function getSummary(): string
    {
        $response = $this->sendRequest('https://www.meteosource.com/api/v1/free/point?lat=' . $this->events->min['lat'] . '&lon=' . $this->events->min['lng'] .'&timezone=UTC&language=cs&units=metric');
        if ($response->code === 200) {
            return json_decode($response->body, true)['current']['summary'];
        }

        return "Nominal";
    }

    /**
     * Get the wind index for the given latitude and longitude.
     *
     * @return string Returns the wind index as a string.
     */
    private function getWindIndex(): string
    {
        $response = $this->sendRequest('https://www.meteosource.com/api/v1/free/point?lat=' . $this->events->min['lat'] . '&lon=' . $this->events->min['lng'] .'&timezone=UTC&language=cs&units=metric');
        if ($response->code === 200) {
            return json_decode($response->body, true)['current']['wind'];
        }

        return "Nominal";
    }

    /**
     * Get the wind icon based on the current wind angle.
     * Uses the Meteosource API to fetch wind information.
     *
     * @return string The name of the wind icon.
     * @throws ConnectionErrorException
     */
    private function getWindIcon(): string
    {
        $response = $this->sendRequest('https://www.meteosource.com/api/v1/free/point?lat=' . $this->events->min['lat'] . '&lon=' . $this->events->min['lng'] .'&timezone=UTC&language=cs&units=metric');
        if ($response->code === 200) {
            $windAngle = json_decode($response->body, true)['current']['wind']['angle'];
            if ($windAngle >= 0 && $windAngle < 45) {
                return "north_east_wind_icon";
            } elseif ($windAngle >= 45 && $windAngle < 90) {
                return "east_wind_icon";
            } elseif ($windAngle >= 90 && $windAngle < 135) {
                return "south_east_wind_icon";
            } elseif ($windAngle >= 135 && $windAngle < 180) {
                return "south_wind_icon";
            } elseif ($windAngle >= 180 && $windAngle < 225) {
                return "south_west_wind_icon";
            } elseif ($windAngle >= 225 && $windAngle < 270) {
                return "west_wind_icon";
            } elseif ($windAngle >= 270 && $windAngle < 315) {
                return "north_west_wind_icon";
            } else {
                return "north_wind_icon";
            }
        }

        return "none_wind_icon";
    }

    /**
     * Send a request to the given URL and return the response.
     *
     * @param string $url The URL to send the request to.
     * @return \Httpful\Response The response from the request.
     * @throws ConnectionErrorException
     */
    private function sendRequest(string $url): \Httpful\Response
    {
        return \Httpful\Request::get($url . '&key=' . env('WEATHER_API_SECRET', ''), "application/json")->expectsJson()->send();
    }
}
