<?php

namespace App\Orchid\Layouts;

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
    protected $type = 'bar';

    protected $target = 'orders';

    protected $title = 'Overall Profit';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;

    public function markers(): ?array
    {
        return [

        ];
    }
}
