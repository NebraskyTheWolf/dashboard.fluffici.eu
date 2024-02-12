<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\ShopCarriers;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class ShopCarrierEdit extends Screen
{

    public $carrier;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ShopCarriers $carrier): iterable
    {
        return [
            'carrier' => $carrier
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->carrier->exists ? "New carrier" : "Edit carrier";
    }

    public function permission(): iterable
    {
        return [
            'platform.shop.carriers.write'
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
            Button::make('Save')
                ->icon('bs.pencil')
                ->method('createOrUpdate'),

            Button::make(__('sales.screen.button.delete'))
                ->icon('bs.trash')
                ->confirm(__('common.modal.confirm'))
                ->method('remove')
                ->canSee($this->carrier->exists),
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
            \Orchid\Support\Facades\Layout::rows([
                Input::make('carrier.slug')
                    ->title('Slug')
                    ->placeholder('Type the name that the system will use.'),

                Input::make('carrier.carrierName')
                    ->title('Carrier name')
                    ->placeholder('Type the name of the delivery service.'),

                Input::make('carrier.carrierPrice')
                    ->title('Carrier price')
                    ->placeholder('Enter the average price of the carrier.'),

                Input::make('carrier.carrierDelay')
                    ->title('Carrier delay')
                    ->placeholder('Enter the average delay of the carrier.'),
            ])->title('Carrier information')
        ];
    }

    public function createOrUpdate(Request $request) {
        $this->carrier->fill($request->get('carrier'))->save();

        Toast::info('Carrier created.');

        event(new UpdateAudit("carrier", $this->carrier->carrierName . " created.", Auth::user()->name));

        return redirect()->route('platform.shop.carriers');
    }

    public function remove(Request $request) {

        $this->carrier->delete();

        return redirect()->route('platform.shop.carriers');
    }
}
