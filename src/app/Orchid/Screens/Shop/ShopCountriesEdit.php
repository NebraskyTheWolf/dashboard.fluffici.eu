<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\ShopCountries;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class ShopCountriesEdit extends Screen
{
    public $country;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ShopCountries $country): iterable
    {
        return [
            'country' => $country
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->country->exists ? 'Edit country' : 'Add country';
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
                ->type(Color::SUCCESS)
                ->method('createOrUpdate'),
            Button::make('Delete')
                ->icon('bs.trash')
                ->type(Color::DANGER)
                ->confirm(__('common.modal.confirm'))
                ->method('remove')
                ->canSee($this->country->exists)
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
                Input::make('country.iso_code')
                    ->title('ISO Code')
                    ->placeholder('Please enter the country iso-code')
                    ->help('Example \'cs\', \'sk\', \'de\''),
                Input::make('country.country_name')
                    ->title('Country name')
                    ->help('Please enter the country name to display.')
            ])
        ];
    }

    public function permission(): iterable
    {
        return [
            'platform.shop.countries.write'
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->country->fill($request->get('country'))->save();

        Toast::info('You added ' . $this->country->country_name);

        event(new UpdateAudit('country', 'Added ' . $this->country->country_name, Auth::user()->name));

        return redirect()->route('platform.shop.countries.list');
    }

    public function remove()
    {
        $this->country->delete();

        Toast::info('You deleted ' . $this->country->country_name);

        event(new UpdateAudit('country', 'Deleted ' . $this->country->country_name, Auth::user()->name));

        return redirect()->route('platform.shop.countries.list');
    }
}
