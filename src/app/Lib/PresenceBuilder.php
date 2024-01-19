<?php

namespace App\Lib;

class PresenceBuilder
{

    public string $slug;
    public array $payload;


    public function __construct(string $slug, array $payload) {
        $this->slug = $slug;
        $this->payload = $payload;
    }
}
