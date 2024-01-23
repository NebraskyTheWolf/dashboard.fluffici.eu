<?php

namespace App\Orchid\Screens\Shop;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

class ShopSupport extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('support.screen.title');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.shop.support.read',
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
            Button::make(__('support.screen.button.add'))
                ->icon('bs.arrow-clockwise')
                ->method('refresh')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [];
    }
}
