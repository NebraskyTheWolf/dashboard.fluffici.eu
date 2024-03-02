<?php

namespace Coderflex\LaravelTurnstile\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Coderflex\LaravelTurnstile\LaravelTurnstile
 */
class LaravelTurnstile extends Facade
{
    /**
     * Get the service container binding key for the facade.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Coderflex\LaravelTurnstile\LaravelTurnstile::class;
    }
}
