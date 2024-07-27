<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\Shop\Internal\ProductTax;
use App\Models\Shop\Internal\ShopProducts;
use App\Models\Shop\Internal\TaxGroup;
use App\Models\Shop\Internal\ShopCategories;
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
     * @param ShopProducts $products
     * @return iterable
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
        return $this->products->exists ? __('Edit Product') : __('Create Product');
    }

    /**
     * Define permissions for accessing this screen.
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.shop.products.write',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return array
     */
    public function commandBar(): iterable
    {
        $currentSale = \App\Models\Shop\Internal\ShopSales::where('product_id', $this->products->id);

        return [
            DropDown::make(__('Actions'))
                ->icon('bs.caret-down')
                ->list([
                    Button::make(__('Save'))
                        ->icon('bs.pencil')
                        ->method('createOrUpdate'),

                    Button::make(__('Delete'))
                        ->method('remove')
                        ->icon('bs.trash'),

                    Link::make($currentSale->exists() ? __('Edit Sale') : __('Create Sale'))
                        ->icon('bs.cash-coin')
                        ->href($currentSale->exists() ? route('platform.shop.sales.edit', $currentSale->firstOrFail()) : route('platform.shop.sales.edit'))
                ])
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
                    Input::make('products.name')
                        ->title(__('Product Name'))
                        ->placeholder(__('Enter the product name'))
                        ->required(),

                    Relation::make('products.category_id')
                        ->title(__('Category'))
                        ->placeholder(__('Select a category'))
                        ->fromModel(ShopCategories::class, 'name', 'id')
                        ->required(),
                ]),

                Group::make([
                    Input::make('products.description')
                        ->type('textarea')
                        ->title(__('Description'))
                        ->placeholder(__('Enter the product description')),

                    Picture::make('Barcode')
                        ->title(__('Barcode'))
                        ->help(__('This is the barcode of this product'))
                        ->url($this->products->exists ? 'https://api.fluffici.eu/api/product/ean?productId=' . $this->products->generateUPCA() : '')
                        ->canSee($this->products->exists),
                ]),

                Group::make([
                    Input::make('products.price')
                        ->title(__('Price'))
                        ->placeholder(__('Enter the product price'))
                        ->type('number')
                        ->required(),

                    Input::make('products.quantity')
                        ->title(__('Quantity'))
                        ->placeholder(__('Enter the inventory quantity'))
                        ->type('number')
                        ->required(),

                    CheckBox::make('products.displayed')
                        ->title(__('Displayed'))
                        ->placeholder(__('Is this product displayed?'))
                        ->checked()
                        ->sendTrueOrFalse(),

                    DateTimer::make('products.deleted_at')
                        ->title(__('Deletion Date'))
                        ->placeholder(__('Select the deletion date'))
                        ->format24hr()
                        ->allowInput(),
                ]),

                Cropper::make('products.image_path')
                    ->title(__('Product Image'))
                    ->placeholder(__('Upload the product image')),
            ])->title(__('Product Information'))
        ];
    }

    /**
     * Create or update a product based on the given request data.
     *
     * @param Request $request The HTTP request object containing the product data.
     *
     * @return RedirectResponse The redirect response to the shop products page.
     */
    public function createOrUpdate(Request $request): RedirectResponse
    {
        $data = $request->get('products');
        $this->products->fill($data);
        $this->products->displayed = $data['displayed'] === "ON";
        $this->products->save();

        $taxes = TaxGroup::all();
        foreach ($taxes as $tax) {
            ProductTax::create([
                'product_id' => $this->products->id,
                'tax_id' => $tax->id,
            ]);
        }

        Toast::info(__('Product saved successfully.'));

        event(new UpdateAudit('products', $this->products->name . ' created.', Auth::user()->name));

        return redirect()->route('platform.shop.products');
    }

    /**
     * Remove a product from the shop.
     *
     * @return RedirectResponse
     */
    public function remove(): RedirectResponse
    {
        $this->products->delete();

        Toast::info(__('Product deleted successfully.'));

        return redirect()->route('platform.shop.products');
    }
}
