<?php

use App\Http\Middleware\EnsureRole;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        App\Providers\AppServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'rol' => EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de errores centralizado (gestion de configuracion de errores):
        // toda excepcion no controlada queda registrada en storage/logs/laravel.log
        // con contexto util (usuario, url, metodo) para poder auditar fallos en produccion,
        // sin exponer nunca detalles tecnicos internos al usuario final.

        $exceptions->report(function (\Throwable $e) {
            // Evita registrar dos veces errores de validacion (no son fallos reales del sistema)
            if ($e instanceof ValidationException) {
                return;
            }

            Log::error('Excepcion no controlada: '.$e->getMessage(), [
                'excepcion' => get_class($e),
                'usuario_id' => auth()->id(),
                'url' => request()?->fullUrl(),
                'metodo' => request()?->method(),
                'archivo' => $e->getFile().':'.$e->getLine(),
            ]);
        });

        // 404: registro no encontrado (ruta invalida o modelo inexistente)
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if (! $request->expectsJson()) {
                return response()->view('errors.404', [], 404);
            }
        });

        // 403: sin permisos (rol no autorizado, ver EnsureRole)
        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            if ($request->expectsJson()) {
                return null;
            }

            return match ($e->getStatusCode()) {
                403 => response()->view('errors.403', [], 403),
                404 => response()->view('errors.404', [], 404),
                419 => response()->view('errors.419', [], 419),
                503 => response()->view('errors.503', [], 503),
                default => null,
            };
        });

        // 401: usuario no autenticado intentando acceder a una ruta protegida
        $exceptions->render(function (AuthenticationException $e, $request) {
            if (! $request->expectsJson()) {
                return redirect()->guest(route('login'))
                    ->with('error', 'Debes iniciar sesion para continuar.');
            }
        });

        // Errores de base de datos (conexion caida, constraint violado, etc.):
        // nunca se muestra el mensaje SQL crudo al usuario final.
        $exceptions->render(function (QueryException $e, $request) {
            if (! $request->expectsJson() && ! config('app.debug')) {
                return response()->view('errors.500', [], 500);
            }
        });
    })->create();
