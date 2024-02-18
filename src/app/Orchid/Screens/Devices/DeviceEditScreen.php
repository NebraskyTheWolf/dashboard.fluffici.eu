<?php

namespace App\Orchid\Screens\Devices;

use App\Models\DeviceAuthorization;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class DeviceEditScreen extends Screen
{

    public $device;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(DeviceAuthorization $device): iterable
    {
        return [
            'device' => $device
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->device ? "Edit device" : "Create new device.";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make("Save")
                ->method('createOrUpdate')
                ->icon("bs.plus")
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
                Input::make("device.deviceId")
                    ->title('Device ID')
                    ->placeholder("Please enter the deviceId.")
                    ->help('You can find it when launching the PDA Application.')
                    ->required(),

                Relation::make('device.linked_user')
                    ->help('Select the user allowed to use this device.')
                    ->title("Select a user")
                    ->fromModel(User::class, 'name', 'id')
                    ->required()
            ])
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->device->status = "Ready";

        $this->device->fill($request->get('device'))->save();

        Toast::info("You created a new device authorization.");

        return redirect()->route('platform.device');
    }
}
