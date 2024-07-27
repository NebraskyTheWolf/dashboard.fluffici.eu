<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Internal\TaxGroup;
use App\Orchid\Layouts\Shop\TaxGroupLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class TaxGroupList extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'groups' => TaxGroup::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Tax groups';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('New')
                ->icon('bs.plus')
                ->href(route('tax.edit.group'))
        ];
    }

    public function permission(): iterable
    {
        return [
            'platform.shop.taxes.read'
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
            TaxGroupLayout::class
        ];
    }


}
