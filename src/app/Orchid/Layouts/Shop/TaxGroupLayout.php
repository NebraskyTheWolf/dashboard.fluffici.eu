<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\TaxGroup;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TaxGroupLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'groups';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Tax Group')
                ->render(function (TaxGroup $group) {
                    return Link::make($group->name)
                        ->icon('bs.pencil')
                        ->href(route('tax.edit.group', $group));
                }),
            TD::make('percentage', 'Percentage')
                ->render(function (TaxGroup $group) {
                    return $group->percentage . '%';
                }),
            TD::make('created_at', 'Created At')
                ->render(function (TaxGroup $group) {
                    return Carbon::parse($group->created_at)->diffForHumans();
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.exposure';
    }

    protected function textNotFound(): string
    {
        return 'No tax group yet.';
    }
}
