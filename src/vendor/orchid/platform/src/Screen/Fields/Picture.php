<?php

declare(strict_types=1);

namespace Orchid\Screen\Fields;

use Orchid\Attachment\Models\Attachment;
use Orchid\Platform\Dashboard;
use Orchid\Screen\Field;
use Orchid\Support\Init;

/**
 * Class Picture.
 *
 * @method Picture acceptedFiles(string $value = null)
 * @method Picture name(string $value = null)
 * @method Picture required(bool $value = true)
 * @method Picture size($value = true)
 * @method Picture src($value = true)
 * @method Picture value($value = true)
 * @method Picture help(string $value = null)
 * @method Picture popover(string $value = null)
 * @method Picture title(string $value = null)
 * @method Picture maxFileSize($value = true)
 * @method Picture storage($value = null)
 * @method Picture groups($value = true)
 */
class Picture extends Field
{

    /**
     * @var string
     */
    protected $view = 'fields.picture';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'autumnUrl' => '',
        'bucket' => '',
        'objectId' => '',
        'width' => 256,
        'height' => 256,
        'readOnly' => false
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [];
}
