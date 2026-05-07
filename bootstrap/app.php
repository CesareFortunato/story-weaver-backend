<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Risposta JSON pulita per route API non trovate o model binding fallito.
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (!$request->is('api/*')) {
                return null;
            }

            $message = $e->getPrevious()?->getMessage() ?? '';

            if (str_contains($message, 'App\\Models\\Node')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nodo non trovato.',
                    'code' => 'NODE_NOT_FOUND',
                ], 404);
            }

            if (str_contains($message, 'App\\Models\\Story')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Storia non trovata.',
                    'code' => 'STORY_NOT_FOUND',
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'Risorsa non trovata.',
                'code' => 'RESOURCE_NOT_FOUND',
            ], 404);
        });
    })->create();