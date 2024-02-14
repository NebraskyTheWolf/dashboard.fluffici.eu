<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Platform\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class FilterByDate extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {

        return 'Date range';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return [
            'pattern.*'
        ];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereDate('created_at', '>=', strtotime($this->getTime($this->request->get('range'))));
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Select::make('range')
                ->title('Select a range')
                ->options([
                    'sevendays' => 'This week',
                    'onemonth' => 'This month',
                    'threemonth' => 'The past 3 months'
                ])->required()
        ];
    }

    public function getTime($str): int
    {
        if ($str === 'sevendays') {
            return '-7 days';
        } else if ($str === 'onemonth') {
            return '-1 month';
        } else if ($str === "threemonth") {
            return '-3 months';
        }

        return '-1 day';
    }
}
