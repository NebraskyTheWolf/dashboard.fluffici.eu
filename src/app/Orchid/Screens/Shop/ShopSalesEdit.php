<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\User;
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

class ShopSalesEdit extends Screen
{
    public $sale;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(\App\Models\ShopSales $sales): iterable
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
        return $this->sale->exists ? __('sales.screen.create.title') : __('sales.screen.edit.title');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.shop.sales.write',
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
            Button::make('Update')
                ->icon('bs.pencil')
                ->method('createOrUpdate'),

            Button::make(__('sales.screen.button.delete'))
                ->icon('bs.trash')
                ->confirm(__('common.modal.confirm'))
                ->method('remove')
                ->canSee($this->sale->exists),
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
                Group::make([
                    Relation::make('sale.product_id')
                        ->title(__('sales.input.product_id.title'))
                        ->placeholder(__('sales.input.product_id.placeholder'))
                        ->fromModel(\App\Models\ShopProducts::class, 'name', 'id'),

                     Input::make('sale.reduction')
                         ->title(__('sales.input.reduction.title'))
                         ->placeholder(__('sales.input.reduction.placeholder')),

                    DateTimer::make('sale.deleted_at')
                        ->title(__('sales.input.deleted_at.title'))
                        ->enableTime()
                        ->format24hr()
                        ->allowInput()
                ])
            ])->title(__('sales.layout.title')),
        ];
    }

    public function createOrUpdate(Request $request) {
        $this->sale->product_type = "";
        $this->sale->fill($request->get('sale'))->save();

        Toast::info(__('sales.toast.created'));

        event(new UpdateAudit("sales", $this->sale->name . " created.", Auth::user()->name));

        return redirect()->route('platform.shop.sales');
    }

    public function remove() {

        $this->sale->delete();
    }
}
