<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Shop\Internal\ShopSales;
use App\Models\Shop\Internal\ShopProducts;

class ShopSalesEdit extends Screen
{
    public $sale;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param ShopSales $sales
     * @return iterable
     */
    public function query(ShopSales $sales): iterable
    {
        return [
            'sale' => $sales
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->sale->exists ? __('Edit Sale') : __('Create Sale');
    }

    /**
     * Define permissions for accessing this screen.
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.shop.sales.write',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return array
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Update'))
                ->icon('bs.pencil')
                ->method('createOrUpdate'),

            Button::make(__('Delete'))
                ->icon('bs.trash')
                ->confirm(__('Are you sure you want to delete this sale?'))
                ->method('remove')
                ->canSee($this->sale->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return array
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    Relation::make('sale.product_id')
                        ->title(__('Product'))
                        ->placeholder(__('Select a product'))
                        ->fromModel(ShopProducts::class, 'name', 'id')
                        ->required(),

                    Input::make('sale.reduction')
                        ->title(__('Reduction (%)'))
                        ->placeholder(__('Enter the reduction percentage'))
                        ->type('number')
                        ->min(0)
                        ->max(100)
                        ->required(),
                ]),

                DateTimer::make('sale.deleted_at')
                    ->title(__('End Date'))
                    ->enableTime()
                    ->format24hr()
                    ->allowInput()
                    ->required()
            ])->title(__('Sale Information')),
        ];
    }

    /**
     * Create or update the sale.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request): RedirectResponse
    {
        $data = $request->get('sale');
        $this->sale->fill($data)->save();

        Toast::info($this->sale->exists ? __('Sale updated successfully.') : __('Sale created successfully.'));

        event(new UpdateAudit('sales', $this->sale->exists ? 'updated' : 'created', Auth::user()->name));

        return redirect()->route('platform.shop.sales');
    }

    /**
     * Remove the sale.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(): RedirectResponse
    {
        $this->sale->delete();

        Toast::info(__('Sale deleted successfully.'));

        return redirect()->route('platform.shop.sales');
    }
}
