<?php

namespace app\Fields;

use Carbon\Carbon;
use Orchid\Screen\Field;

/**
 * Class Field.
 *
 * @method self setTime($value = Carbon::now())
 */
class RelativeTime extends Field
{
    public $view = 'fields.relative-time';

    public $attributes = [
        'date' => ''
    ];
}
