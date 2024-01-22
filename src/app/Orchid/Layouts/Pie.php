<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Chart;

class Pie extends Chart
{
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'pie';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;
}
