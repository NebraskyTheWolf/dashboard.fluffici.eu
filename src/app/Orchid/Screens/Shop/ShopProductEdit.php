<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\ProductTax;
use App\Models\ShopCategories;
use App\Models\ShopProducts;
use App\Models\ShopSales;
use App\Models\TaxGroup;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ShopProductEdit extends Screen
{

    public $products;
    public $currentTax;


    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ShopProducts $products): iterable
    {
        return [
            'products' => $products
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->products->exists ? __('products.screen.edit.title') : __('products.screen.edit.title.create');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        if ($this->products->exists) {
            $currentSale = ShopSales::where('product_id', $this->products->id);

            return [
                DropDown::make(__('products.screen.edit.dropdown.menu'))
                    ->icon('bs.caret-down')
                    ->list([
                        Button::make(__('products.screen.edit.button.save'))
                            ->icon('bs.pencil')
                            ->method('createOrUpdate'),
                        Button::make(__('products.screen.edit.button.remove'))
                            ->method('remove')
                            ->icon('bs.trash'),

                        Link::make($currentSale->exists() ? __('products.screen.edit.button.edit_sale') : __('products.screen.edit.button.new_sale'))
                            ->icon('bs.cash-coin')
                            ->href($currentSale->exists() ? route('platform.shop.sales.edit', $currentSale->firstorFail()) : route('platform.shop.sales.edit'))
                    ])
            ];
        }

        return [
            DropDown::make(__('products.screen.edit.dropdown.menu'))
                ->icon('bs.caret-down')
                ->list([
                    Button::make(__('products.screen.edit.button.save'))
                        ->icon('bs.pencil')
                        ->method('createOrUpdate')
                ])
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'platform.shop.products.write',
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
                    Input::make('products.name')
                        ->title(__('products.screen.edit.input.product_name.title'))
                        ->placeholder(__('products.screen.edit.input.product_name.placeholder'))
                        ->required(),

                    Relation::make('products.category_id')
                        ->title(__('products.screen.edit.input.category_id.title'))
                        ->placeholder(__('products.screen.edit.input.category_id.placeholder'))
                        ->fromModel(ShopCategories::class, 'name', 'id')
                        ->required()

                ])->alignStart(),

                Group::make([
                    Quill::make('products.description')
                        ->title(__('products.screen.edit.input.description.title'))
                        ->placeholder(__('products.screen.edit.input.description.placeholder'))
                        ->base64()
                        ->autofocus(),

                    Picture::make('Barcode')
                        ->title('Barcode')
                        ->help('This is the barcode of this product')
                        ->url($this->products->exists ? route('api.shop.barcode') . '?productId=' . $this->products->generateUPCA() : '')
                        ->canSee($this->products->exists)

                ])->alignCenter(),

                Group::make([
                    Input::make('products.price')
                        ->title(__('products.screen.edit.input.price.title'))
                        ->placeholder(__('products.screen.edit.input.price.placeholder'))
                        ->type('number')
                        ->required(),

                    CheckBox::make('products.displayed')
                        ->title(__('products.screen.edit.input.displayed.title'))
                        ->placeholder(__('products.screen.edit.input.displayed.placeholder'))
                        ->checked()
                        ->sendTrueOrFalse(),

                    DateTimer::make('products.deleted_at')
                        ->title(__('products.screen.edit.input.deleted_at.title'))
                        ->placeholder(__('products.screen.edit.input.deleted_at.placeholder'))
                        ->format24hr()
                        ->allowInput()


                ])->alignEnd(),

                Cropper::make('products.image_path')
                    ->title(__('products.screen.edit.input.image_path.title'))
                    ->placeholder(__('products.screen.edit.input.image_path.placeholder'))

            ])->title(__('products.screen.edit.input.title'))
        ];
    }

    /**
     * Create or update a product based on the given request data.
     *
     * @param Request $request The HTTP request object containing the product data.
     *
     * @return RedirectResponse The redirect response to the shop products page.
     */
    public function createOrUpdate(Request $request)
    {
        $data = $this->products->fill($request->get('products'));

        if ($data->displayed === "ON") {
            $data->displayed = true;
        } else {
            $data->displayed = false;
        }

        $data->save();

        $taxes = TaxGroup::paginate();
        foreach ($taxes as $taxs) {
            $tax = new ProductTax();
            $tax->product_id = ShopProducts::latest()->first()->id;
            $tax->tax_id = $taxs->id;
            $tax->save();
        }

        Toast::info('You created a new product.');

        event(new UpdateAudit("products", $this->products->name . " created.", Auth::user()->name));

        return redirect()->route('platform.shop.products');
    }

    /**
     * Remove a product from the shop.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove() {
        $this->products->delete();

        return redirect()->route('platform.shop.products');
    }
}
