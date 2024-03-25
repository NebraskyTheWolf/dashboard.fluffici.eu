<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\ProductInventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class InventoryEditScreen extends Screen
{

    public $inventory;

    public function query(ProductInventory $inventory): iterable
    {
        return [
            'inventory' => $inventory
        ];
    }


    public function name(): ?string
    {
        return $this->inventory->exists ? 'Edit inventory.' : 'Create new inventory.';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.eshop.inventory.write'
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->method('createOrUpdate')
                ->type(Color::SUCCESS)
                ->icon('bs.pencil')
        ];
    }


    public function layout(): iterable
    {
        return [
            Layout::rows([
                Relation::make('inventory.product_id')
                    ->fromModel(\App\Models\ShopProducts::class, 'name', 'id')
                    ->title('Product')
                    ->disabled(),
                Input::make('inventory.available')
                    ->type('number')
                    ->title('Available stock')
                    ->placeholder('Please enter the current available stock.')
            ])
        ];
    }

    /**
     * Creates or updates the inventory based on the given request.
     *
     * @param Request $request The request containing the inventory data.
     * @return RedirectResponse The redirect response to the inventory page.
     */
    public function createOrUpdate(Request $request): RedirectResponse
    {
        $this->inventory->fill($request->get('inventory'))->save();

        Toast::success("Inventory saved.");

        event(new UpdateAudit($this->inventory->exists ? 'product_inventory_saved' : 'product_inventory_created', 'Inventory saved.', Auth::user()->name));

        return redirect()->route('platform.inventory');
    }
}
