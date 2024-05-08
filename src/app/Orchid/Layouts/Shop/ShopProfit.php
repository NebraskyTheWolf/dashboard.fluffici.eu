<?php

namespace App\Orchid\Layouts\Shop;

use Orchid\Screen\Layouts\Chart;

class ShopProfit extends Chart
{
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'line';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;
}
