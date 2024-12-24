<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Configuration\Exceptions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {

            Route::middleware('web')->prefix('student-inbound')->group(base_path('routes/student_inbound.php'));

            Route::middleware('web')->prefix('student-outbound')->group(base_path('routes/student_outbond.php'));

            Route::middleware('web')->prefix('staff-inbound')->group(base_path('routes/staff_inbound.php'));

            Route::middleware('web')->prefix('mitra-akademik')->group(base_path('routes/mitra_akademik.php'));

        }
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->redirectGuestsTo('/');

        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
         // Handle 403 Error (Forbidden)
         $exceptions->render(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                return response()->view('errors.error403', [], 403);
            }
        });

        // Handle 404 Error (Not Found)
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->view('errors.error404', [], 404);
        });

        // Handle 500 Error (Internal Server Error)
        $exceptions->render(function (Throwable $e, $request) {
            if ($e->getCode() === 500 || $e instanceof \ErrorException) {
                return response()->view('errors.error500', [], 500);
            }
        });
    })->create();

