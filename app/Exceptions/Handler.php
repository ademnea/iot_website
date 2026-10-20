<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

        // Password reset emails need working mail settings (MAIL_* in .env). When the mail server
        // can't be reached, tell the person what happened instead of showing an error page.
        $this->renderable(function (TransportExceptionInterface $e, $request) {
            if ($request->is('forgot-password')) {
                report($e);

                return back()->withInput($request->only('email'))->withErrors([
                    'email' => "The reset email couldn't be sent because email isn't set up on this server yet. "
                        .'Ask the site administrator to reset your password.',
                ]);
            }
        });
    }
}
