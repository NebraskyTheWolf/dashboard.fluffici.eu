<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Seo;

use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;


class SeoSelectLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('seo.tags')
                ->type('text')
                ->required()
                ->title(__('Tags'))
                ->placeholder(__('Tags'))
                ->help('Add a tag and add \',\' at the end.')
        ];
    }
}
