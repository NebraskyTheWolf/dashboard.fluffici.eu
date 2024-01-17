<?php

declare(strict_types=1);

namespace Orchid\Screen\Actions;

use Orchid\Screen\Action;
use Orchid\Support\Facades\Dashboard;

/**
 * Class Button.
 *
 * @method Button name(string $name = null)
 * @method Button modal(string $modalName = null)
 * @method Button icon(string $icon = null)
 * @method Button class(string $classes = null)
 * @method Button confirm(string $confirm = true)
 * @method Button action(string $url)
 * @method Button disabled(bool $disabled = true)
 */
class Attach extends Action {
        /**
     * @var string
     */
    protected $view = 'fields.attach';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'maxCount'            => 1,
        'maxSize'             => 400, // MB
        'accept'              => '*/*',
        'placeholder'         => 'Upload file',
        'errorMaxSizeMessage' => 'File ":name" is too large to upload',
        'errorTypeMessage'    => 'The attached file must be an image',
        'actionId' => '',
        'remoteTag' => 'attachments',
        'userId' => '',
        'autumnHost' => '',
        'callbackSucceed' => '',
        'callbackFailed' => '',
        'objectId' => ''
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        'accept',
        'required'
    ];
}