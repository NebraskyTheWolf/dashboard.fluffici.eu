<?php

namespace Coderflex\LaravelTurnstile;

use Coderflex\LaravelTurnstile\Exceptions\SecretKeyNotFoundException;
use Coderflex\LaravelTurnstile\Exceptions\UnknownErrorOccurredException;
use Illuminate\Support\Facades\Http;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class LaravelTurnstile
{
    protected ?string $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Validates the given cloudflare turnstile response.
     *
     * @param string|null $cfResponse The cloudflare turnstile response string. If null, it will retrieve the response from the request query parameter "cf-turnstile-response".
     *
     * @return array Returns an array containing the validation response from the turnstile API. The array will have the following structure:
     *               - success: A boolean indicating whether the validation was successful.
     *               - message: A string describing the validation result or an error message if an unknown error occurred.
     *               If the response from the turnstile API is empty, it will return a default error message.
     *
     * @throws SecretKeyNotFoundException If the turnstile secret key is not defined in the application configuration.
     */
    public function validate(string $cfResponse = null): array
    {
        $turnstileResponse = is_null($cfResponse)
            ? request()->get('cf-turnstile-response')
            : $cfResponse;

        if (! $secret = config('turnstile.turnstile_secret_key')) {
            throw new SecretKeyNotFoundException('Turnstile secret key is not defined.');
        }

        $response = Http::asJson()
            ->timeout(30)
            ->connectTimeout(10)
            ->throw(
                fn () => new UnknownErrorOccurredException(
                    'An unknown error occurred, please refresh the page and try again.'
                )
            )
            ->post($this->getUrl(), [
                'secret' => $secret,
                'response' => $turnstileResponse,
            ]);

        return count($response->json())
            ? $response->json()
            : [
                'success' => false,
                'message' => 'Unknown error occurred, please try again',
            ];
    }

    /**
     * Retrieves the URL of the object.
     *
     * @return string|null The URL of the object.
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}
