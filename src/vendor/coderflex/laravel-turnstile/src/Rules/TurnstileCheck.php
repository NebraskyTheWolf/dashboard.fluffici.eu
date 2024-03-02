<?php

namespace Coderflex\LaravelTurnstile\Rules;

use Closure;
use Coderflex\LaravelTurnstile\Facades\LaravelTurnstile;
use Illuminate\Contracts\Validation\ValidationRule;

class TurnstileCheck implements ValidationRule
{
    /**
     * Validate the given attribute value using LaravelTurnstile.
     *
     * @param string $attribute The name of the attribute being validated.
     * @param mixed $value The value being validated.
     * @param Closure $fail The callback function to be executed if the validation fails.
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = LaravelTurnstile::validate($value);

        if (! $response['success']) {
            $fail(__(config('turnstile.error_messages.turnstile_check_message')));
        }
    }
}
