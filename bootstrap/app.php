<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Configura e avvia l'applicazione Laravel.
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // File delle rotte web.
        web: __DIR__ . '/../routes/web.php',

        // File delle rotte API.
        api: __DIR__ . '/../routes/api.php',

        // File dei comandi console.
        commands: __DIR__ . '/../routes/console.php',

        // Route di health check dell'applicazione.
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Personalizza le risposte 404 per le API.
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {

            // Se la richiesta non è API, lascia gestire l'errore a Laravel.
            if (!$request->is('api/*')) {
                return null;
            }

            // Recupera il messaggio dell'eccezione precedente,
            // utile per capire quale model non è stato trovato.
            $message = $e->getPrevious()?->getMessage() ?? '';

            // Risposta specifica quando non viene trovato un Node.
            if (str_contains($message, 'App\\Models\\Node')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nodo non trovato.',
                    'code' => 'NODE_NOT_FOUND',
                ], 404);
            }

            // Risposta specifica quando non viene trovata una Story.
            if (str_contains($message, 'App\\Models\\Story')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Storia non trovata.',
                    'code' => 'STORY_NOT_FOUND',
                ], 404);
            }

            // Risposta generica per altre risorse non trovate.
            return response()->json([
                'success' => false,
                'message' => 'Risorsa non trovata.',
                'code' => 'RESOURCE_NOT_FOUND',
            ], 404);
        });
    })->create();