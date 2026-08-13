<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'set.locale' => SetLocale::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(
            function (
                AuthenticationException $exception,
                Request $request
            ) {
                if ($request->is('api/*')) {

                    $locale = strtolower(
                        substr(
                            $request->header(
                                'Accept-Language',
                                'en'
                            ),
                            0,
                            2
                        )
                    );

                    if (!in_array($locale, ['en', 'ar'])) {
                        $locale = 'en';
                    }

                    app()->setLocale($locale);

                    return response()->json([
                        'success' => false,
                        'message' => __('messages.unauthenticated'),
                    ], 401);
                }

                return null;
            }
        );

    })

    ->create();