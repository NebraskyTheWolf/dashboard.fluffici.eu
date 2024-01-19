<?php

declare(strict_types=1);

namespace Orchid\Platform\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Class UploadFailed.
 */
class UploadFailed
{
    use SerializesModels;

    public $type;

    /**
     * UploadFailed constructor.
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }
}
