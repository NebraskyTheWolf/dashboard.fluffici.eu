<?php

namespace App\Orchid\Screens;

use App\Models\WebsocketApps;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class RealTimeAppEdit extends Screen
{

    public $application;

    public function query(WebsocketApps $application): iterable
    {
        return [
            'application' => $application
        ];
    }


    public function name(): ?string
    {
        return $this->application->exists ? 'Edit application' : 'New application';
    }


    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->type(Color::SUCCESS)
                ->icon('bs.pencil')
                ->method('createOrUpdate'),

            Button::make($this->application->enabled ? 'Disable' : 'Enable')
                ->type($this->application->enabled ? Color::SUCCESS : Color::DANGER)
                ->icon('bs.pencil')
                ->method('switchEnabledState')
                ->canSee($this->application->exists),
        ];
    }

    public function layout(): iterable {
        if (!$this->application->exists) {
            $this->application->id = $this->generateNumericToken(6);
            $this->application->key = substr(str_replace('-', '', Uuid::uuid4()->toString()), 0, 20);
            $this->application->secret = substr(str_replace('-', '', Uuid::uuid4()->toString()), 0, 20);

            $this->application->enabled = true;
            $this->application->webhooks = json_encode([]);
        }

        return [
            Layout::rows([
                Group::make([
                    Input::make('application.application_name')
                        ->title('Application name'),

                    Input::make('application.id')
                        ->title('Application id'),

                    Input::make('application.key')
                        ->title('Application key')
                        ->help("The public key of the application."),

                    Input::make('application.secret')
                        ->title('Application secret')
                        ->help("The private key of the application."),

                    CheckBox::make('application.enable_client_messages')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('enable_client_messages'),
                ])->alignCenter(),

                Group::make([
                    Input::make('application.max_connections')
                        ->type('number')
                        ->title('Maximum connections')
                        ->value(100)
                        ->help('The maximum allowed connection on this application.'),

                    Input::make('application.max_backend_events_per_sec')
                        ->type('number')
                        ->title('Max BEvent/s')
                        ->value(1000)
                        ->help('The maximum of backend event per second.'),

                    Input::make('application.max_client_events_per_sec')
                        ->type('number')
                        ->title('Max CEvent/s')
                        ->value(500)
                        ->help('The maximum of client event per second.'),

                    Input::make('application.max_read_req_per_sec')
                        ->type('number')
                        ->title('Max RReq/s')
                        ->value(500)
                        ->help('The maximum of read request per second.'),
                ])->alignEnd(),

                Group::make([
                    CheckBox::make('application.max_presence_members_per_channel')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('max_presence_members_per_channel'),

                    CheckBox::make('application.max_presence_member_size_in_kb')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('max_presence_members_per_channel'),

                    CheckBox::make('application.max_event_channels_at_once')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('max_event_channels_at_once'),

                    CheckBox::make('application.max_channel_name_length')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('max_channel_name_length')
                ])->alignEnd(),

                Group::make([
                    CheckBox::make('application.max_event_name_length')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('max_event_name_length'),

                    CheckBox::make('application.max_event_payload_in_kb')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('max_event_payload_in_kb'),

                    CheckBox::make('application.max_event_batch_size')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('max_event_batch_size'),

                    CheckBox::make('application.enable_user_authentication')
                        ->sendTrueOrFalse()
                        ->value(false)
                        ->title('enable_user_authentication')
                ])->alignEnd()
            ])
        ];
    }

    public function createOrUpdate(Request $request): RedirectResponse
    {
        $this->application->fill($request->get('application'))->save();

        Toast::info('Application saved successfully');

        return redirect()->route('platform.realtime');
    }

    public function switchEnabledState(Request $request): RedirectResponse {
        if ($this->application->enabled == 1) {
            $this->application->enabled = 0;

            Toast::success("You disabled the application " . $this->application->application_name);
        } else {
            $this->application->enabled = 1;

            Toast::success("You enabled the application " . $this->application->application_name);
        }

        $this->application->save();

        return redirect()->route('platform.realtime');
    }

    private function generateNumericToken(int $length = 4): string
    {
        $i = 0;
        $token = "";

        while ($i < $length) {
            $token .= random_int(0, 9);
            $i++;
        }

        return $token;
    }
}
