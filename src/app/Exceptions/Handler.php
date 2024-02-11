<?php

namespace App\Exceptions;

use App\Mail\ApplicationError;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($this->isHttpException($e)) {
            if ($e->getStatusCode() == 404) {
                return response()->view('errors.404', [], 404);
            }
            if ($e->getStatusCode() == 500) {

                Mail::to([
                    'vakea@fluffici.eu'
                ])->send(new ApplicationError(
                        $e->getFile(),
                        $e->getMessage(),
                        $e->getLine(),
                        $e->getTraceAsString())
                );

                return response()->view('errors.500', [], 500);
            }
            if ($e->getStatusCode() == 403) {
                return response()->view('errors.403', [], 403);
            }
        }
        return parent::render($request, $e);
    }
}
