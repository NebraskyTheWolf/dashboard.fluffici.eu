<?php

namespace App\Orchid\Screens;

use App\Models\DeviceAuthorization;
use App\Orchid\Layouts\DeviceList;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Symfony\Component\HttpFoundation\Request;

class DeviceScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'devices' => DeviceAuthorization::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Authorized devices.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make("New device")
                ->icon('bs.plus')
                ->type(Color::SUCCESS)
                ->href(route('platform.device.new'))
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
            DeviceList::class
        ];
    }

    public function restrictDevice(Request $request)
    {

    }
}
