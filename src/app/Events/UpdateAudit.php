<?php

declare(strict_types=1);

namespace App\Events;

/**
 * Class UpdateAudit
 *
 * Represents an update audit object.
 */
class UpdateAudit
{

    public $slug;
    public $type;
    public $username;

    public function __construct(string $slug, string $type, string $username)
    {
        $this->slug = $slug;
        $this->type = $type;
        $this->username = $username;
    }
}
